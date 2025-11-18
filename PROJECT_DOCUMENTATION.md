# MaatriSetu – Internal Architecture

## 1. System Context

- Runtime: Apache + PHP 8.x via XAMPP, MySQL 10.4 (MariaDB compatible).
- Entry points:
  - Anonymous: `index.php`, `login.html`.
  - Authenticated: any page requires `$_SESSION['user_id']`; guard added in `reminders.php`, `community.php`, etc.
- Frontend assets are embedded per page (Tailwind CDN, inline JS). No build step.

## 2. Module Inventory

| Area | Files | Notes |
| --- | --- | --- |
| Session/Auth | `login.html`, `send_otp.php`, `verify_otp.php`, `logout.php` | OTP issuance, validation, session bootstrap. |
| User Profile | `profile.php`, `save_details.php`, `save_language.php`, `save_mood.php` | CRUD on `users` + `mood_log`. |
| Dashboard | `index.php` | Mood widget, quick links, blog feed, testimonials. |
| Reminders | `reminders.php`, `create_reminder.php`, `get_reminders.php`, `delete_reminder.php` | CRUD + client scheduling. |
| Community | `community.php`, `create_post.php`, `get_posts.php`, `like_post.php`, `delete_post.php`, `images/` | Feed, reactions, attachments. |
| Guidance/Schemes | `guidance.php`, `schemes.php`, `schemes.js`, `community.css` | Static+JS-driven content. |
| Hospital Search | `hospital_search.php`, `api_config.php` | Maps + OSM integration. |
| Chatbot | `chatbot_api.php` | Gemini API proxy. |
| Config/DB | `database.php`, `config.php` (ignored), `sms_config.php` | Connection + credential management. |
| Data Dump | `maatrisetu_db.sql` | Schema + seed records. |

## 3. Backend Interfaces

### Auth/Session Service
- `send_otp.php`: validates phone, writes OTP to session/db, dispatches via provider defined in `sms_config.php['service']`.
- `verify_otp.php`: checks OTP, sets `$_SESSION['user_id']` (existing user or creates stub), redirects to `profile.php`.

### Reminder Service
- Storage: `reminders` table.
- Endpoints: `create_reminder.php`, `get_reminders.php`, `delete_reminder.php`.
- Business rules: require login, sanitize input, default `notify_relative=0`, response payload normalized to `{id,type,date,time,...}` for frontend.

### Community Service
- Storage: `posts`, `post_likes`.
- Endpoints: `create_post.php` (insert), `get_posts.php` (select sorted), `like_post.php` (upsert into `post_likes` and aggregate), `delete_post.php` (ownership enforced).

### Hospital Service
- `hospital_search.php`: fetches user address from DB, geocodes via Google, queries Places + Overpass, merges responses, returns normalized facility list.
- `api_config.php` houses API keys/constants.

### Chatbot Service
- `chatbot_api.php`: server-side POST to `GEMINI_API_URL` using `GEMINI_API_KEY`.
- Request throttling/error logging handled inline; frontend receives sanitized text.

## 4. Client-Side Flows

### Reminders UI (`reminders.php`)
1. On load: enforce auth, request Notification permission, fetch reminders.
2. `reminders` array stored in JS; `displayReminders()` renders sorted cards, toggles empty state.
3. `scheduleReminderNotifications()` sets `setTimeout` for each future reminder; timers cleared/rebuilt after CRUD.
4. Form submit ➜ `create_reminder.php` (JSON). On success push to array, re-render, reschedule.

### Community UI (`community.php`)
1. Initial `get_posts.php` fetch populates feed.
2. Post form hits `create_post.php`; appended to DOM without reload.
3. Reaction buttons call `like_post.php`; counts updated in-place.
4. Delete button calls `delete_post.php`; removes post, optimistic update.

### Hospital Widget (sidebar in `reminders.php`)
1. `findNearbyFacilities()` POSTs to `hospital_search.php`.
2. Response cached in `allHospitals`; first 5 shown with “view more” toggle.
3. On errors, UI shows retry CTA.

## 5. External Integrations

| Integration | Config Source | Consumer |
| --- | --- | --- |
| OTP SMS (Twilio/TextLocal/Fast2SMS/Test) | `sms_config.php` | `send_otp.php` |
| Google Places + Geocoding | `api_config.php` constants | `hospital_search.php` |
| Overpass API | `api_config.php` constants | `hospital_search.php` |
| Google Gemini | `api_config.php` constants | `chatbot_api.php` |

All keys currently hard-coded for demo; replace with env-driven configuration before production.

## 6. Data Model (excerpt from `maatrisetu_db.sql`)

### `users`
- Identity: `id` PK, `contactNumber`, `is_phone_verified`.
- Profile: `fullName`, `address`, `pregnancyStage`, `weeksPregnant`, `languagePref`, `healthConditions`, `familyIncome`.
- Emergency contact: `relativeName`, `relativeRelation`, `relativePhone`.
- Community metadata: `username`, `bio`, `profile_character_url`.

### `reminders`
- Foreign key to `users.id` (cascade delete).
- Fields: `reminder_type`, `reminder_date`, `reminder_time`, `description`, `notify_relative`, `is_completed`, timestamps.
- Indices: `idx_user_date (user_id, reminder_date)`, `idx_date_time (reminder_date, reminder_time)`.

### `posts` / `post_likes`
- `posts`: user_id, user_name snapshot, content, likes counter, created_at.
- `post_likes`: `(post_id, user_id)` unique pair to enforce single reaction; `is_like` reserved for future toggles.

### `mood_log`
- Unique constraint per `(user_id, log_date)` for idempotent daily entries.

Seed rows populate demo users, reminders, and posts for UI verification.

## 7. Configuration + Secrets

| File | Responsibility | Notes |
| --- | --- | --- |
| `config.php` (ignored) | Returns associative array: `['database' => ['host','username','password','database','charset']]`. | Required by `database.php`. |
| `sms_config.php` | Returns provider map. | Switch providers by changing `'service'`. |
| `api_config.php` | Defines Google/Gemini keys, API URLs, search params. | Should not ship with production keys. |
| `.gitignore` | Ensure above files, dumps, and credentials aren’t tracked. | Already present. |

## 8. Deployment Considerations

- HTTPS mandatory for Notification API and secure OTP forms.
- Harden session settings (`session.cookie_secure`, `session.cookie_httponly`).
- Rate-limit OTP endpoints; add expiry/attempt counters.
- Move API keys to Apache env (`SetEnv` or `.htaccess`) and load via `getenv`.
- For scheduled reminder delivery beyond in-browser notifications, add cron/queue worker hitting SMS/email channels referencing `reminders` table.

## 9. Reference Snippets

```1:120:reminders.php
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html?redirect=reminders.php');
    exit;
}
...
```

```7:36:database.php
function getDatabaseConnection() {
    $config_file = __DIR__ . '/config.php';
    if (!file_exists($config_file)) {
        throw new Exception('Configuration file not found...');
    }
    $config = require $config_file;
    ...
```

```1:60:create_reminder.php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
...
$stmt = $conn->prepare("INSERT INTO reminders (user_id, reminder_type, reminder_date, reminder_time, description, notify_relative) VALUES (?, ?, ?, ?, ?, ?)");
```

```97:144:maatrisetu_db.sql
CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reminder_type` varchar(50) NOT NULL,
  ...
INSERT INTO `users` ...
```

Document is scoped to internals; operational setup and onboarding remain in `README.md`.


