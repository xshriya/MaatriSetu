-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 08:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maatrisetu_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `mood_log`
--

CREATE TABLE `mood_log` (
  `mood_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mood` varchar(50) NOT NULL,
  `log_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `user_name`, `content`, `likes`, `created_at`) VALUES
(6, 9, 'Priya Sharma', 'Home remedy for morning sickness that really worked for me: Mix 1 teaspoon of fresh ginger juice with honey and have it first thing in the morning. Also, keeping crackers by my bedside and eating them before getting up helped tremendously. Small frequent meals instead of three large ones made a huge difference. Stay hydrated with lemon water throughout the day! 🍋', 24, '2025-11-11 19:35:30'),
(9, 9, 'Priya Sharma', 'Just had my 20-week anatomy scan at Cooper Hospital, Andheri. The experience was wonderful! The staff was so patient in explaining everything. They checked baby\'s heart, brain, spine, and all organs. Took about 45 minutes. For moms in Mumbai, I highly recommend their prenatal care department. They also have good government schemes available. Remember to drink plenty of water before the scan! 💙', 18, '2025-11-10 21:35:30'),
(11, 6, 'Priya Sharma', 'Pregnancy yoga has been a game-changer for my back pain! Started with simple breathing exercises and gentle stretches. Cat-cow pose, child\'s pose, and pelvic tilts are amazing. Always consult your doctor first and find a certified prenatal yoga instructor. I practice 20 minutes daily and my energy levels have improved so much. It also helps with sleep and reduces stress! 🧘‍♀️', 31, '2025-11-08 21:35:30'),
(14, 10, 'Anjali Verma', 'Managing gestational diabetes naturally: My doctor at Max Hospital Rohini helped me create a perfect diet plan. Key tips - eat every 2-3 hours, include protein with every meal, choose complex carbs like brown rice and whole wheat. Avoid white bread, sugary drinks, and processed foods. Walk for 30 minutes after meals. My blood sugar is now well controlled! Regular monitoring is crucial. Don\'t skip your glucose tests! 📊', 42, '2025-11-11 17:35:30'),
(15, 10, 'Anjali Verma', 'Iron-rich foods that helped me beat pregnancy anemia: Spinach curry with lemon (vitamin C helps absorption), dates soaked overnight, pomegranate juice, beetroot salad, and jaggery instead of sugar. My hemoglobin went from 9.5 to 11.2 in just 6 weeks! Also taking prescribed iron supplements with orange juice, not tea. Remember, tea and coffee reduce iron absorption. Consult your doctor for proper supplementation! 🥬', 38, '2025-11-09 21:35:30'),
(16, 10, 'Anjali Verma', 'Hospital bag essentials from my first pregnancy experience: Pack two bags - one for labor and one for postpartum. Must-haves: comfortable nightgowns (front opening for nursing), maternity pads, nursing bras, baby clothes in newborn and 0-3 months size, diapers, wet wipes, your medical records, insurance papers, and snacks! Don\'t forget phone charger and a comfortable pillow. Pack by 35 weeks! 🏥', 29, '2025-11-06 21:35:30'),
(17, 11, 'Meera Patel', 'First trimester survival tips that saved me: Ginger tea with tulsi leaves in the morning, small frequent meals every 2 hours, avoiding spicy and oily foods, and staying hydrated with coconut water. Vitamin B6 supplements (doctor prescribed) helped reduce nausea. Keep dry snacks like khakhra or crackers handy. Rest is crucial - don\'t push yourself! This phase will pass, usually by week 12-14. Hang in there, mamas! 💪', 35, '2025-11-11 16:35:30'),
(18, 11, 'Meera Patel', 'My first prenatal visit at Civil Hospital Ahmedabad was very informative! They did complete blood work, urine test, blood pressure check, and dating ultrasound. The doctor explained about folic acid importance (prevents neural tube defects), calcium needs, and what foods to avoid. Government hospitals provide free iron and calcium tablets! They also registered me for Pradhan Mantri Matru Vandana Yojana. Don\'t miss your prenatal appointments! 🏥', 27, '2025-11-07 21:35:30'),
(19, 12, 'Sneha Reddy', 'Managing high blood pressure during pregnancy naturally (along with medication): Reduce salt intake, eat potassium-rich foods like bananas and sweet potatoes, practice deep breathing exercises daily, get adequate sleep (7-8 hours), and avoid stress. My doctor at Apollo Hospital Banjara Hills monitors me weekly. Regular BP checks at home are important. Never skip your medications! Preeclampsia is serious, so follow medical advice strictly. Your health = baby\'s health! ❤️', 33, '2025-11-11 15:35:30'),
(20, 12, 'Sneha Reddy', 'Coping with pregnancy after loss - my journey: After my miscarriage, this pregnancy brings both joy and anxiety. What helps: regular checkups (every 2 weeks), talking to a counselor, joining support groups, practicing mindfulness, and celebrating small milestones. Every ultrasound where I see the heartbeat is a victory! Don\'t hesitate to seek emotional support. Your feelings are valid. Government hospitals have free counseling services. You\'re not alone! 🌈', 45, '2025-11-08 21:35:30'),
(21, 12, 'Sneha Reddy', 'Natural remedies for swollen feet that worked: Elevate legs for 20 minutes 3-4 times daily, gentle foot massage with coconut oil, reduce salt intake, stay hydrated (it seems counterintuitive but it works!), wear comfortable footwear, and do ankle rotations. Soak feet in warm water with Epsom salt for 15 minutes. Avoid standing for long periods. If swelling is sudden or severe, contact your doctor immediately as it could indicate preeclampsia! 🦶', 28, '2025-11-05 21:35:30'),
(22, 13, 'Kavya Nair', 'Preparing for labor - breathing techniques I learned: Practice deep belly breathing daily. During contractions: breathe in through nose for 4 counts, hold for 2, breathe out through mouth for 6 counts. This activates parasympathetic nervous system and reduces pain perception. I attended free prenatal classes at General Hospital Ernakulam. They taught positions for labor, when to go to hospital, and pain management techniques. Knowledge reduces fear! 🫁', 37, '2025-11-11 20:35:30'),
(23, 13, 'Kavya Nair', 'Nutrition in third trimester - what I\'m eating: Protein-rich foods (dal, eggs, paneer, chicken), calcium sources (milk, yogurt, ragi), iron-rich foods (spinach, dates, pomegranate), and healthy fats (ghee, nuts, avocado). Eating 6 small meals daily. Avoiding junk food, excess sugar, and raw foods. Staying hydrated with 3-4 liters of water. My baby\'s growth is perfect! Good nutrition = healthy baby. Consult a nutritionist if needed! 🥗', 32, '2025-11-09 21:35:30'),
(24, 13, 'Kavya Nair', 'Nesting phase tips - organizing smartly: Washed all baby clothes with gentle detergent and sun-dried them. Organized nursery with easy-access storage for nighttime diaper changes. Prepared freezer meals for postpartum (soups, parathas, curries). Made a list of important contacts (doctor, hospital, family). Packed hospital bag and kept it ready. Pre-registered at hospital to avoid paperwork during labor. Being prepared reduces stress! 🏡', 25, '2025-11-04 21:35:30'),
(25, 14, 'Ritu Singh', 'Dealing with pregnancy anemia - my recovery story: My hemoglobin was 8.5 at 16 weeks. Doctor at KGMU Lucknow prescribed iron supplements and diet changes. I started eating iron-rich foods: green leafy vegetables, beetroot, pomegranate, dates, jaggery, and raisins. Important tip: take iron tablets with vitamin C (orange juice) for better absorption, avoid tea/coffee 2 hours before and after. After 8 weeks, my hemoglobin is 11! Regular blood tests are crucial! 💊', 40, '2025-11-11 13:35:30'),
(26, 14, 'Ritu Singh', 'Budget-friendly pregnancy nutrition tips: Buy seasonal vegetables and fruits (cheaper and fresher), make homemade snacks like roasted chana, prepare dal-rice combinations for complete protein, use jaggery instead of expensive supplements for iron, drink homemade buttermilk for probiotics. Government rations provide wheat, rice, and dal. Focus on traditional Indian foods - they\'re nutritious and affordable! Healthy pregnancy doesn\'t need to be expensive! 🌾', 34, '2025-11-06 21:35:30'),
(27, 15, 'Divya Iyer', 'Working through third trimester - tips that helped me: Take frequent breaks every hour, keep healthy snacks at desk (nuts, fruits), stay hydrated, use ergonomic chair with back support, elevate feet under desk, do gentle stretches, and communicate with your manager about needs. I worked from home 2 days a week. Know your rights - maternity leave, work from home options. Your health comes first! Don\'t hesitate to take sick leave if needed! 💼', 37, '2025-11-11 11:35:30'),
(28, 15, 'Divya Iyer', 'Choosing between government and private hospitals - my research: Visited both Bowring Hospital (government) and Cloudnine Hospital (private) in Bangalore. Government hospitals: free/low cost, experienced doctors, good for normal deliveries, may be crowded. Private: expensive, more comfortable, personalized care, better for high-risk pregnancies. Many government hospitals now have excellent facilities! Check reviews, visit beforehand, ask about emergency protocols. Choose based on your medical needs and budget! 🏥', 41, '2025-11-07 21:35:30'),
(29, 15, 'Divya Iyer', 'Prenatal vitamins and supplements explained: Folic acid (400-800 mcg) prevents birth defects, take from conception through first trimester. Calcium (1000 mg) for baby\'s bone development. Iron (27 mg) prevents anemia. Vitamin D for calcium absorption. DHA for brain development. My doctor prescribed a comprehensive prenatal vitamin. Never self-prescribe! Excess vitamins can be harmful. Government hospitals provide free iron and calcium tablets. Regular blood tests help monitor deficiencies! 💊', 30, '2025-11-03 21:35:31');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_like` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reminder_type` varchar(50) NOT NULL,
  `reminder_date` date NOT NULL,
  `reminder_time` time NOT NULL,
  `description` text DEFAULT NULL,
  `notify_relative` tinyint(1) DEFAULT 0,
  `is_completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `address` text NOT NULL,
  `contactNumber` varchar(15) NOT NULL,
  `is_phone_verified` tinyint(1) DEFAULT 0,
  `pregnancyStage` varchar(20) DEFAULT NULL,
  `weeksPregnant` int(11) DEFAULT NULL,
  `languagePref` varchar(20) DEFAULT NULL,
  `familyIncome` int(11) DEFAULT NULL,
  `healthConditions` text DEFAULT NULL,
  `hadMiscarriage` varchar(5) DEFAULT NULL,
  `previousMiscarriages` int(11) DEFAULT NULL,
  `relativeName` varchar(100) DEFAULT NULL,
  `relativeRelation` varchar(50) DEFAULT NULL,
  `relativePhone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_character_url` varchar(255) DEFAULT 'images/avatars/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `age`, `address`, `contactNumber`, `is_phone_verified`, `pregnancyStage`, `weeksPregnant`, `languagePref`, `familyIncome`, `healthConditions`, `hadMiscarriage`, `previousMiscarriages`, `relativeName`, `relativeRelation`, `relativePhone`, `created_at`, `username`, `bio`, `profile_character_url`) VALUES
(9, 'Priya Sharma', 28, 'Andheri West, Mumbai, Maharashtra - 400053', '9876543210', 1, 'Second Trimester', 20, 'English', 50000, 'None', 'No', 0, 'Raj Sharma', 'Husband', '9876543211', '2025-11-11 21:31:09', NULL, 'First time mom, excited and nervous! 💕', 'images/avatars/avatar2.png'),
(10, 'Anjali Verma', 32, 'Sector 7, Rohini, Delhi - 110085', '9876543220', 1, 'Third Trimester', 35, 'Hindi', 75000, 'Gestational Diabetes', 'No', 0, 'Vikram Verma', 'Husband', '9876543221', '2025-11-11 21:31:09', NULL, 'Second baby on the way! Experienced mom here to help 🤰', 'images/avatars/avatar3.png'),
(11, 'Meera Patel', 26, 'Satellite Road, Ahmedabad, Gujarat - 380015', '9876543230', 1, 'First Trimester', 8, 'Gujarati', 45000, 'None', 'No', 0, 'Kiran Patel', 'Husband', '9876543231', '2025-11-11 21:31:09', NULL, 'Just found out! So many emotions 💗', 'images/avatars/avatar4.png'),
(12, 'Sneha Reddy', 30, 'Banjara Hills, Hyderabad, Telangana - 500034', '9876543240', 1, 'Second Trimester', 24, 'mr', 60000, 'High Blood Pressure', 'Yes', 1, 'Arun Reddy', 'Husband', '9876543241', '2025-11-11 21:31:09', NULL, 'Rainbow baby coming soon 🌈', 'images/avatars/avatar5.png'),
(13, 'Kavya Nair', 29, 'MG Road, Ernakulam, Kochi, Kerala - 682016', '9876543250', 1, 'Third Trimester', 38, 'Malayalam', 55000, 'None', 'No', 0, 'Suresh Nair', 'Husband', '9876543251', '2025-11-11 21:31:09', NULL, 'Almost there! Can\'t wait to meet my little one 👶', 'images/avatars/avatar1.png'),
(14, 'Ritu Singh', 27, 'Gomti Nagar Extension, Lucknow, Uttar Pradesh - 226010', '9876543260', 1, 'Second Trimester', 18, 'Hindi', 48000, 'Anemia', 'No', 0, 'Amit Singh', 'Husband', '9876543261', '2025-11-11 21:31:09', NULL, 'Learning and growing with my baby 🌸', 'images/avatars/avatar3.png'),
(15, 'Divya Iyer', 31, 'Indiranagar, Bangalore, Karnataka - 560038', '9876543270', 1, 'Third Trimester', 32, 'Kannada', 85000, 'None', 'No', 0, 'Ramesh Iyer', 'Husband', '9876543271', '2025-11-11 21:31:09', NULL, 'Tech professional turned full-time mom soon! 💻➡️👶', 'images/avatars/avatar2.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mood_log`
--
ALTER TABLE `mood_log`
  ADD PRIMARY KEY (`mood_id`),
  ADD UNIQUE KEY `daily_mood` (`user_id`,`log_date`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reaction` (`post_id`,`user_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_date` (`user_id`,`reminder_date`),
  ADD KEY `idx_date_time` (`reminder_date`,`reminder_time`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mood_log`
--
ALTER TABLE `mood_log`
  MODIFY `mood_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mood_log`
--
ALTER TABLE `mood_log`
  ADD CONSTRAINT `mood_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
