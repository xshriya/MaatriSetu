  <?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// --- MOOD CHECKING LOGIC ---
$current_mood = null;
if (isset($_SESSION['user_id'])) {
    require_once 'database.php';
    try {
        $conn = getDatabaseConnection();
    } catch (Exception $e) {
        // Handle connection error silently for display purposes
        $conn = null;
    }

    if ($conn) {
        $user_id = $_SESSION['user_id'];
        $today_date = date('Y-m-d');
        
        $sql = "SELECT mood FROM mood_log WHERE user_id = ? AND log_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $today_date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $current_mood = $row['mood'];
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo isset($_SESSION['user_name']) ? 'MaatriSetu - Home' : 'MaatriSetu - Sign Up'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    
    <?php if (isset($_SESSION['user_name'])): ?>
    <style>
        #typewriter-heading {
            overflow: hidden; white-space: nowrap; margin: 0 auto; letter-spacing: .1em;
        }
        .mood-selector {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin: 15px 0;
        }
        .mood-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 10px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            min-width: 80px;
            max-width: 95px;
        }
        .mood-option:hover {
            border-color: #ec4899;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15);
        }
        .mood-option.selected {
            border-color: #ec4899;
            background: #fdf2f8;
        }
        .mood-emoji {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        .mood-option:hover .mood-emoji {
            transform: scale(1.15);
        }
        .mood-name {
            font-size: 0.75rem;
            font-weight: 500;
            color: #374151;
            text-align: center;
            line-height: 1.2;
            word-wrap: break-word;
            hyphens: auto;
            margin-top: 5px;
        }
        .mood-question {
            color: #ec4899 !important;
            font-weight: 600;
        }
    </style>
    <?php endif; ?>
</head>

<body class="bg-pink-100 text-gray-800">
    <header class="bg-white shadow py-4 mb-6">
    <div class="container mx-auto flex items-center justify-between px-4">
      <div class="flex items-center gap-2">
        <img src="logo.jpeg" alt="MaatriSetu Logo" class="h-10 w-auto" />
        <span class="text-xl font-bold text-pink-600">MaatriSetu</span>
      </div>
      <nav>
        <ul class="flex gap-6 text-base font-medium">
          <li><a href="index.php" class="hover:text-pink-600">Home</a></li>
          <li><a href="community.php" class="hover:text-pink-600">Community</a></li>
          <li><a href="guidance.php" class="hover:text-pink-600">Guidance</a></li>
          <li><a href="schemes.php" class="hover:text-pink-600">Schemes</a></li>
          <li><a href="reminders.php" class="hover:text-pink-600">Reminders</a></li>
          <li><a href="f-a-q.html" class="hover:text-pink-600">FAQ</a></li>
          <?php if (isset($_SESSION['user_name'])): ?>
            <li class="relative">
                <button id="userMenuButton" class="w-10 h-10 rounded-full bg-pink-600 text-white flex items-center justify-center focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </button>
                <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                    <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Settings</a>
                    <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Logout</a>
                </div>
            </li>
          <?php else: ?>
            <li><a href="login.html" class="hover:text-pink-600">Login</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

    <!-- Hero Section -->
    <section class="text-center px-6 mb-12">
        <img src="banner.png" alt="Mother and Baby Banner" class="w-full h-80 object-cover rounded-lg shadow" />
        
        <?php if (isset($_SESSION['user_name'])): ?>
            <!-- Logged-in User - Show typewriter name and mood tracker instead of title and button -->
            <h2 id="typewriter-heading" class="text-3xl font-semibold text-pink-600 mt-6">
                Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            </h2>
            
            <!-- Mood Tracker instead of "Your trusted guide" text and Get Started button -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-3 mood-question">
                    <?php echo $current_mood ? 'Today you are feeling:' : 'How are you feeling today?'; ?>
                </h3>
                <div class="mood-selector">
                    <?php
                    $moods = ['Joyful' => '😊', 'Anxious' => '😟', 'Irritable' => '😠', 'Sad' => '😢', 'Overwhelmed' => '😩'];
                    foreach ($moods as $name => $emoji):
                        $isSelected = ($current_mood === $name);
                    ?>
                        <div class="mood-option <?php echo $isSelected ? 'selected' : ''; ?>" data-mood="<?php echo $name; ?>">
                            <div class="mood-emoji"><?php echo $emoji; ?></div>
                            <div class="mood-name"><?php echo $name; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p id="mood-status" class="mt-4 text-sm text-gray-500 h-4 text-center"></p>
            </div>
        <?php else: ?>
            <!-- Non-logged-in User - Show original title and Get Started button -->
            <h2 class="text-3xl font-semibold text-pink-600 mt-6">Empowering Every Step of Motherhood</h2>
            <p class="mt-3 text-gray-600">Your trusted guide for health, support, and care during pregnancy and beyond.</p>
            <button onclick="location.href='login.html'" class="mt-4 px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">Get Started</button>
        <?php endif; ?>
    </section>


  <!-- Quick Access Section -->
<section class="max-w-6xl mx-auto px-6 mb-12">
  <h2 class="text-2xl font-bold text-pink-600 text-center mb-6">Quick Access</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- Card 1 -->
    <div class="bg-white p-6 rounded-lg shadow text-center">
      <!-- Icon -->
       <svg xmlns="http://www.w3.org/2000/svg"class="h-10 w-10 mx-auto text-pink-600 mb-3" viewBox="0 0 24 24" fill="None" stroke="currentColor" >
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.75 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM7.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM8.25 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9.75 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM10.5 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM12.75 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM14.25 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
  <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
</svg>
      <h3 class="font-semibold text-lg text-gray-700">Track Your Stage</h3>
      <p class="mt-2 text-gray-600">Stage-wise health tips and guidance.</p>
        <button onclick="location.href='guidance.php'" class="mt-4 w-14 h-10 bg-pink-600 text-white rounded-lg hover:bg-pink-700 flex items-center justify-center mx-auto">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
          </svg>
        </button>
    </div>

    <!-- Card 2 -->
    <div class="bg-white p-6 rounded-lg shadow text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-pink-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h3 class="font-semibold text-lg text-gray-700">Reminders</h3>
      <p class="mt-2 text-gray-600">Never miss supplements or check-ups.</p>
        <button onclick="location.href='reminders.php'" class="mt-4 w-14 h-10 bg-pink-600 text-white rounded-lg hover:bg-pink-700 flex items-center justify-center mx-auto">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
          </svg>
        </button>
    </div>

    <!-- Card 3 -->
    <div class="bg-white p-6 rounded-lg shadow text-center">
       
         <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-pink-600 mb-3" viewBox="0 0 24 24" fill="None" stroke="currentColor">
  <path stroke-linecap = "round" stroke-linejoin="round" stroke-width="2" d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
</svg>
      <h3 class="font-semibold text-lg text-gray-700">Community</h3>
      <p class="mt-2 text-gray-600">Connect with other mothers and families.</p>
        <button onclick="location.href='community.php'" class="mt-4 w-14 h-10 bg-pink-600 text-white rounded-lg hover:bg-pink-700 flex items-center justify-center mx-auto">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
          </svg>
        </button>
    </div>

    <!-- Card 4 -->
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-pink-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
</svg>
      <h3 class="font-semibold text-lg text-gray-700">Schemes</h3>
      <p class="mt-2 text-gray-600">Find and apply for government benefits.</p>
      <button onclick="location.href='schemes.php'" class="mt-4 w-14 h-10 bg-pink-600 text-white rounded-lg hover:bg-pink-700 flex items-center justify-center mx-auto">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
        </svg>
      </button>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="bg-white max-w-6xl mx-auto px-6 py-8 rounded-lg shadow mb-12 pl-12">
<h2 class="text-2xl font-bold text-pink-600 text-center mb-6">Our Features</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<h3 class="font-semibold text-lg ml-24 text-gray-700">Personalized Reminders</h3>
<p class="text-gray-600 ml-24">Stay on track with timely alerts.</p>
</div>
<div>
<h3 class="font-semibold text-lg ml-24 text-gray-700">Emotional Support</h3>
<p class="text-gray-600 ml-24 ">Check-ins and a safe space to share.</p>
</div>
 <div>
<h3 class="font-semibold text-lg ml-24 text-gray-700">Stage-Wise Guidance</h3>
<p class="text-gray-600 ml-24">Care tips for each phase of motherhood.</p>
</div>
<div>
<h3 class="font-semibold text-lg ml-24 text-gray-700">Government Schemes Access</h3>
<p class="text-gray-600 ml-24">Easy info on maternity benefits.</p>
</div>
</div>
</section>

  <!-- Blog Section -->
  <section class="max-w-3xl mx-auto px-6 mb-12">
    <h2 class="text-2xl font-bold text-pink-600 mb-4 text-center">Latest from Our Blog</h2>
    <article class="bg-white p-6 rounded-lg shadow">
      <h3 class="font-semibold text-lg text-gray-700">5 Essential Tips for a Healthy Pregnancy</h3>
      <p class="mt-2 text-gray-600">Discover easy steps to stay fit and healthy during your pregnancy journey. Simple nutrition, exercise, and wellness guidance at your fingertips...</p>
      <a href="guidance.php" class="text-pink-600 font-medium hover:underline mt-2 inline-block">Read More</a>
    </article>
  </section>

  <!-- Newsletter Section -->
  <section class="bg-pink-100 max-w-3xl mx-auto px-6 py-8 rounded-lg shadow mb-12">
    <h2 class="text-2xl font-bold text-pink-600 mb-4 text-center">Subscribe to Our Newsletter</h2>
    <form class="space-y-4">
      <div>
        <label for="name" class="block font-medium text-gray-700">Name:</label>
        <input type="text" id="name" required class="w-full border border-gray-300 rounded-lg p-2" />
      </div>
      <div>
        <label for="email" class="block font-medium text-gray-700">Email:</label>
        <input type="email" id="email" required class="w-full border border-gray-300 rounded-lg p-2" />
      </div>
      <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded-lg hover:bg-pink-700">Subscribe</button>
    </form>
  </section>

  <!-- Reviews Section -->
  <section class="max-w-4xl mx-auto px-4 py-8 mb-12">
    <h2 class="text-2xl font-bold text-pink-600 mb-6 text-center">What Mothers Say</h2>

    <div id="testimonial-carousel" class="relative overflow-hidden bg-white rounded-lg shadow-lg">
        <div class="carousel-wrapper flex transition-transform duration-500 ease-in-out">
            
            <div class="carousel-slide flex-shrink-0 w-full p-8 text-center">
                <blockquote class="italic text-gray-700 text-lg">"MaatriSetu helped me track my pregnancy week by week. The guidance was simple and easy to understand. Highly recommended!"</blockquote>
                <figcaption class="mt-4 text-gray-600 font-semibold">– Anita, 28</figcaption>
            </div>

            <div class="carousel-slide flex-shrink-0 w-full p-8 text-center">
                <blockquote class="italic text-gray-700 text-lg">"The reminders for supplements and check-ups were a lifesaver. I felt so much more organized and less stressed."</blockquote>
                <figcaption class="mt-4 text-gray-600 font-semibold">– Rani, 30</figcaption>
            </div>

            <div class="carousel-slide flex-shrink-0 w-full p-8 text-center">
                <blockquote class="italic text-gray-700 text-lg">"I found amazing support in the community section. Connecting with other mothers made me feel less alone in my journey."</blockquote>
                <figcaption class="mt-4 text-gray-600 font-semibold">– Kavita, 26</figcaption>
            </div>
            
        </div>

        <button id="prev-btn" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-white/50 hover:bg-white rounded-full p-2 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button id="next-btn" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-white/50 hover:bg-white rounded-full p-2 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
    </div>
</section>

    <!-- Footer -->
    <footer class="bg-white text-center py-6 shadow-inner">
        <div class="mb-2 space-x-4">
            <a href="f-a-q.html" class="text-pink-600 hover:underline">FAQ</a>
            <a href="community.php" class="text-pink-600 hover:underline">Community</a>
            <a href="#" class="text-pink-600 hover:underline">Privacy Policy</a>
            <a href="#" class="text-pink-600 hover:underline">Terms of Service</a>
        </div>
        <div class="mb-2 text-gray-700">
            📧 Email: <a href="mailto:support@maatrisetu.org" class="text-pink-600 hover:underline">support@maatrisetu.org</a> |
            ☎ Phone: <a href="tel:+919876543210" class="text-pink-600 hover:underline">+91-9876543210</a>
        </div>
        <div class="mb-2 text-gray-700">
            Follow us:
            <a href="#" class="text-pink-600 hover:underline">Facebook</a>
            <a href="#" class="text-pink-600 hover:underline">Instagram</a>
            <a href="#" class="text-pink-600 hover:underline">Twitter</a>
        </div>
        <p class="text-gray-700">© 2025 MaatriSetu. All Rights Reserved.</p>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Dropdown Menu Script
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenuDropdown = document.getElementById('userMenuDropdown');

        if (userMenuButton) {
            userMenuButton.addEventListener('click', () => {
                userMenuDropdown.classList.toggle('hidden');
            });
            window.addEventListener('click', (e) => {
                if (userMenuButton && !userMenuButton.contains(e.target) && userMenuDropdown && !userMenuDropdown.contains(e.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }

        <?php if (isset($_SESSION['user_name'])): ?>
        // Typewriter Effect for logged-in users
        const heading = document.getElementById('typewriter-heading');
        if (heading) {
            const text = heading.innerText;
            heading.innerText = '';
            heading.style.borderRight = '3px solid #be185d';
            let i = 0;
            function typeWriter() {
                if (i < text.length) {
                    heading.innerText += text.charAt(i);
                    i++;
                    setTimeout(typeWriter, 100);
                } else {
                    setTimeout(() => { heading.style.borderRight = 'none'; }, 1000);
                }
            }
            typeWriter();
        }

        // Mood Tracker Script
        const moodOptions = document.querySelectorAll('.mood-option');
        const moodStatus = document.getElementById('mood-status');

        moodOptions.forEach(option => {
            option.addEventListener('click', async () => {
                const selectedMood = option.dataset.mood;

                moodOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');

                if(moodStatus) moodStatus.textContent = 'Saving your mood...';

                try {
                    const response = await fetch('save_mood.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ mood: selectedMood })
                    });
                    const result = await response.json();

                    if (result.success) {
                        if(moodStatus) moodStatus.textContent = 'Your mood has been updated!';
                    } else {
                        if(moodStatus) moodStatus.textContent = 'Error: ' + result.message;
                    }
                } catch (error) {
                    if(moodStatus) moodStatus.textContent = 'Error saving your mood.';
                }

                setTimeout(() => {
                    if(moodStatus) moodStatus.textContent = '';
                }, 2000);
            });
        });
        <?php else: ?>
        // Login form JavaScript (same as login.html)
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const otpSection = document.getElementById('otpSection');
        const contactNumberInput = document.getElementById('contactNumber');
        const otpInput = document.getElementById('otp');
        const saveDetailsBtn = document.getElementById('saveDetailsBtn');
        const otpStatus = document.getElementById('otpStatus');
        const detailsForm = document.getElementById('detailsForm');
        const mainHeading = document.getElementById('main-heading');
        const profileHeading = document.getElementById('profile-heading');
        const phoneSection = document.getElementById('phone-section');
        const profileFields = document.getElementById('profile-fields');

        if (sendOtpBtn) {
            sendOtpBtn.addEventListener('click', async () => {
                const phone = contactNumberInput.value;
                if (phone.length < 10) {
                    alert('Please enter a valid 10-digit phone number.');
                    return;
                }
                sendOtpBtn.disabled = true;
                sendOtpBtn.textContent = 'Sending...';
                try {
                    const response = await fetch('send_otp.php', { 
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ phone: phone })
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        otpSection.classList.remove('hidden');
                        otpStatus.textContent = 'OTP has been sent.';
                    } else {
                        throw new Error(result.message || 'Failed to send OTP.');
                    }
                } catch (error) {
                    otpStatus.textContent = error.message;
                    otpStatus.style.color = 'red';
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.textContent = 'Send OTP';
                }
            });
        }

        if (otpInput) {
            otpInput.addEventListener('input', async () => {
                const otp = otpInput.value;
                const phone = contactNumberInput.value;
                if (otp.length === 6) {
                    try {
                        const response = await fetch('verify_otp.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ phone: phone, otp: otp })
                        });
                        const result = await response.json();
                        
                        if (result.success) {
                            otpStatus.textContent = 'Phone number verified!';
                            otpStatus.style.color = 'green';
                            
                            if (result.profile_complete) {
                                otpStatus.textContent = 'Welcome back! Redirecting...';
                                setTimeout(() => {
                                    window.location.href = 'index.php';
                                }, 1500);
                            } else {
                                saveDetailsBtn.disabled = false;
                                mainHeading.classList.add('hidden');
                                phoneSection.classList.add('hidden');
                                otpSection.classList.add('hidden');
                                profileHeading.classList.remove('hidden');
                                profileFields.classList.remove('hidden');
                            }
                        } else {
                            otpStatus.textContent = 'Invalid OTP. Please try again.';
                            otpStatus.style.color = 'red';
                        }
                    } catch (error) {
                        otpStatus.textContent = 'Verification failed. Please try again.';
                        otpStatus.style.color = 'red';
                    }
                }
            });
        }
        
        if (detailsForm) {
            detailsForm.addEventListener('submit', () => {
                contactNumberInput.disabled = false; 
            });
        }
        <?php endif; ?>
    });
    </script>
    <!-- Chatbot Widget -->
<div id="chatbotContainer" class="fixed bottom-5 right-5 z-50">
    <!-- Chat Button -->
    <button id="chatbotToggle" class="bg-pink-500 hover:bg-pink-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-105">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chatbotWindow" class="hidden absolute bottom-20 right-0 w-80 bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-pink-500 text-white p-3.5 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center overflow-hidden">
                    <img src="logo.jpeg" alt="SetuBot" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-semibold text-sm">SetuBot</h3>
                    <p class="text-xs text-pink-100">Online • Ready to help</p>
                </div>
            </div>
            <button id="chatbotClose" class="text-white hover:bg-pink-600 rounded-full p-1 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Messages Container -->
        <div id="chatMessages" class="h-80 overflow-y-auto p-3.5 bg-gradient-to-b from-pink-50 to-white space-y-2.5">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-2">
                <div class="w-7 h-7 bg-white rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 overflow-hidden border border-pink-200">
                    <img src="logo.jpeg" alt="SetuBot" class="w-full h-full object-cover">
                </div>
                <div class="bg-white rounded-lg rounded-tl-none p-2.5 shadow-sm max-w-[220px] border border-pink-100">
                    <p class="text-xs text-gray-800 leading-relaxed">Hi! I'm SetuBot, your MaatriSetu Assistant. How can I help you today? 💕</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-100">
            <div class="flex space-x-2">
                <input type="text" id="chatInput" placeholder="Type your message..." 
                       class="flex-1 border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent text-xs placeholder-gray-400">
                <button id="chatSend" class="bg-pink-500 text-white rounded-lg px-3.5 py-2 hover:bg-pink-600 transition-colors flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
            <p class="text-[8px] text-gray-400 mt-2 text-center">
                Ask about pregnancy, health tips & more
            </p>
        </div>
    </div>
</div>

<script>
// Chatbot functionality
const chatbotToggle = document.getElementById('chatbotToggle');
const chatbotWindow = document.getElementById('chatbotWindow');
const chatbotClose = document.getElementById('chatbotClose');
const chatInput = document.getElementById('chatInput');
const chatSend = document.getElementById('chatSend');
const chatMessages = document.getElementById('chatMessages');

// Toggle chat window
chatbotToggle.addEventListener('click', () => {
    chatbotWindow.classList.toggle('hidden');
    if (!chatbotWindow.classList.contains('hidden')) {
        chatInput.focus();
    }
});

chatbotClose.addEventListener('click', () => {
    chatbotWindow.classList.add('hidden');
});

// Add message to chat
function addMessage(message, isUser = false) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex items-start space-x-2 ${isUser ? 'justify-end' : ''}`;
    
    if (isUser) {
        messageDiv.innerHTML = `
            <div class="bg-pink-500 text-white rounded-lg rounded-tr-none p-2.5 shadow-sm max-w-[220px]">
                <p class="text-xs leading-relaxed">${message}</p>
            </div>
            <div class="w-7 h-7 bg-pink-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <span class="text-base">👤</span>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="w-7 h-7 bg-white rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 overflow-hidden border border-pink-200">
                <img src="logo.jpeg" alt="SetuBot" class="w-full h-full object-cover">
            </div>
            <div class="bg-white rounded-lg rounded-tl-none p-2.5 shadow-sm max-w-[220px] border border-pink-100">
                <p class="text-xs text-gray-800 leading-relaxed">${message}</p>
            </div>
        `;
    }
    
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Add typing indicator
function showTyping() {
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typingIndicator';
    typingDiv.className = 'flex items-start space-x-2';
    typingDiv.innerHTML = `
        <div class="w-7 h-7 bg-white rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 overflow-hidden border border-pink-200">
            <img src="logo.jpeg" alt="SetuBot" class="w-full h-full object-cover">
        </div>
        <div class="bg-white rounded-lg rounded-tl-none p-2.5 shadow-sm border border-pink-100">
            <div class="flex space-x-1">
                <div class="w-1.5 h-1.5 bg-pink-400 rounded-full animate-bounce"></div>
                <div class="w-1.5 h-1.5 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-1.5 h-1.5 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function hideTyping() {
    const typingIndicator = document.getElementById('typingIndicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

// Send message
async function sendMessage() {
    const message = chatInput.value.trim();
    if (!message) return;
    
    // Add user message
    addMessage(message, true);
    chatInput.value = '';
    
    // Show typing indicator
    showTyping();
    
    try {
        const response = await fetch('chatbot_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: message })
        });
        
        const data = await response.json();
        hideTyping();
        
        if (data.success) {
            addMessage(data.response);
        } else {
            addMessage('Sorry, I encountered an error. Please try again! 😊');
            console.error('Chatbot error:', data.message);
            console.error('Full error details:', data);
        }
    } catch (error) {
        hideTyping();
        addMessage('Sorry, I\'m having trouble connecting. Please try again later! 😊');
        console.error('Chatbot error:', error);
    }
}

// Event listeners
chatSend.addEventListener('click', sendMessage);
chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
</script>
</body>
</html>