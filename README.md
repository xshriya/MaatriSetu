# MaatriSetu - Empowering Every Step of Motherhood

**MaatriSetu** is a comprehensive health platform designed for expectant and new mothers. It provides personalized guidance, health reminders, community support, and easy access to nearby healthcare facilities—all in one place.

## 🎯 Key Features

- **Secure Sign-Up** – OTP verification via SMS ensures safe account creation
- **Health Reminders** – Set and receive browser notifications for check-ups, medications, vaccinations, and prenatal appointments
- **Personalized Dashboard** – Track mood, view stage-specific health tips, and access quick shortcuts
- **Community Forum** – Connect with other mothers, share experiences, and get peer support
- **Health Guidance** – Trimester-specific advice, FAQs, and government health schemes
- **Find Hospitals** – Discover nearby healthcare facilities with contact details and services
- **AI Health Assistant** – Ask health questions and get instant answers from an AI chatbot
- **Mood Tracking** – Monitor emotional well-being throughout your pregnancy journey

## 📋 What You Need

- **XAMPP** (Apache + PHP 8.1+ + MySQL)
- **API Keys:** Google Maps (for hospital search) and Gemini (for chatbot)
- **SMS Service:** Twilio, TextLocal, or Fast2SMS for OTP verification

## ⚡ Quick Start

### 1. Set Up the Database
- Start MySQL in XAMPP
- Open phpMyAdmin: `http://localhost/phpmyadmin`
- Import `maatrisetu_db.sql` to create all tables

### 2. Configure API Keys
- Open `sms_config.php` and add your SMS service credentials (Twilio/TextLocal/Fast2SMS)
- Open `api_config.php` and add your Google Maps and Gemini API keys
- For testing, set `'service' => 'test'` in `sms_config.php` to log OTPs instead of sending SMS

### 3. Start the Application
- Launch Apache and MySQL in XAMPP
- Visit `http://localhost/Maatrisetu/` in your browser
- Sign up with an OTP to create an account

## 📱 How to Use

**Sign Up**
- Click "Sign Up" and enter your phone number
- Receive an OTP via SMS
- Verify the OTP and complete your profile

**Dashboard**
- View your mood tracker
- See health tips for your stage
- Access quick shortcuts to all features

**Set Health Reminders**
- Go to "Reminders"
- Create reminders for check-ups, medications, vaccinations
- Receive browser notifications at the scheduled time
- Manage your reminders anytime

**Join the Community**
- Share your experiences with other mothers
- Like on posts
- Get support and advice from the community

**Get Health Information**
- Read trimester-specific guidance
- Learn about government health schemes
- Browse FAQs

**Find Hospitals**
- Search for nearby healthcare facilities
- View hospital details and contact information
- Get directions and services offered

**Ask the AI Assistant**
- Chat with the AI for health-related questions
- Get instant, reliable answers
- Available 24/7

## 🔧 Troubleshooting

**OTP not sending?**
- Check your SMS service credentials in `sms_config.php`
- Ensure your SMS service account has active balance
- Use `'service' => 'test'` to test without sending SMS

**Hospital search not working?**
- Verify Google Maps API is enabled and billing is active
- Check API keys in `api_config.php`

**Reminders not showing?**
- Enable browser notifications when prompted
- Check browser notification settings
- Ensure the reminder time is in the future

**Database connection failed?**
- Verify MySQL is running in XAMPP
- Check database credentials match your setup
- Ensure `maatrisetu_db.sql` was imported successfully

## 📁 Project Structure

```
Maatrisetu/
├── index.php              # Dashboard
├── login.html             # Sign-up & Login
├── reminders.php          # Health reminders
├── community.php          # Community forum
├── guidance.php           # Health guidance
├── schemes.php            # Government schemes
├── hospital_search.php    # Find hospitals
├── profile.php            # User profile
├── f-a-q.html             # FAQs
├── sms_config.php         # SMS service config
├── api_config.php         # API keys config
└── images/                # User uploads
```

## �️ Tech Stack

**Frontend**
- HTML5 for structure
- Tailwind CSS for styling
- Vanilla JavaScript for interactivity

**Backend**
- PHP 8.1+ for server logic
- MySQL 5.7/8.0 for database
- RESTful JSON APIs for data exchange

**External Services**
- **SMS Gateway:** Twilio, TextLocal, or Fast2SMS for OTP delivery
- **Maps & Location:** Google Maps Platform (Places API, Geocoding API)
- **Chatbot:** Google Gemini API for AI-powered health Q&A
- **Geospatial Data:** OpenStreetMap & Overpass API for hospital data

**Development Tools**
- XAMPP for local development
- phpMyAdmin for database management
- Git for version control

## 🤝 How to Contribute

We welcome contributions! Follow these steps:

### 1. Fork & Clone
Fork the repository and clone it to your local machine.

### 2. Create a Feature Branch
Use descriptive branch names for your changes.

### 3. Make Your Changes
- Follow the existing code style in the project
- Test your changes locally
- Ensure all features work correctly

### 4. Test Before Submitting
- Sign-up with OTP verification
- Create and delete reminders
- Community posts and likes
- Hospital search functionality
- Chatbot responses
- Mood tracking and profile updates

### 5. Submit a Pull Request
Include:
- Clear description of what you changed
- Related issue links (if applicable)
- Testing details


## �� License

MIT License 


