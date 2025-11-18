<?php
session_start();

// 1. DATABASE CONNECTION
// (Add your database connection script here)
// Database connection
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// 2. CHECK IF USER IS LOGGED IN
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 3. HANDLE FORM SUBMISSION (POST Request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $bio = $_POST['bio'];
    $profile_character = $_POST['profile_character'];

    // Basic validation
    if (!empty($username) && !empty($profile_character)) {
        
        // Prepare UPDATE statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE users SET fullName = ?, bio = ?, profile_character_url = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $bio, $profile_character, $user_id);

        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
        } else {
            // Check for duplicate username
            if ($conn->errno == 1062) {
                $message = "Error: This username is already taken. Please choose another.";
            } else {
                $message = "Error updating profile: " . $conn->error;
            }
        }
        $stmt->close();
    } else {
        $message = "Username and a profile character are required.";
    }
}

// 4. FETCH CURRENT USER DATA (GET Request)
$stmt = $conn->prepare("SELECT fullName, contactNumber, bio, profile_character_url FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Helper function to check the current avatar for the radio button
function is_checked($current_avatar, $option_avatar) {
    if ($current_avatar == $option_avatar) {
        echo 'checked';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MaatriSetu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for the avatar selector */
        input[type="radio"]:checked + label img {
            border: 4px solid #f58ec3; /* Pink border when selected */
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-pink-50 font-sans">

    <header class="bg-white shadow py-4">
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
                    <li><a href="profile.php" class="text-pink-600 font-bold">Profile</a></li>
                    <li><a href="logout.php" class="hover:text-pink-600">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container mx-auto mt-10 p-4 md:p-8">
        
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            
            <div class="bg-gradient-to-r from-pink-400 to-pink-500 p-8 text-center">
                <?php 
                    $avatarUrl = !empty($user['profile_character_url']) ? htmlspecialchars($user['profile_character_url']) : 'images/avatars/avatar1.png';
                ?>
                <img src="<?php echo $avatarUrl; ?>" alt="Profile Avatar" class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg" onerror="this.src='images/avatars/avatar1.png'">
                <h1 class="text-3xl font-bold text-white mt-4"><?php echo htmlspecialchars($user['fullName']); ?></h1>
                <p class="text-pink-100">Phone: <?php echo htmlspecialchars($user['contactNumber']); ?></p>
            </div>

            <form method="POST" action="profile.php" class="p-8 space-y-6">
                
                <?php if (!empty($message)): ?>
                    <div class="p-4 rounded-md <?php echo strpos($message, 'Error') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-lg font-semibold text-gray-800 mb-3">Choose Your Avatar</label>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <?php 
                            $avatars = [
                                'images/avatars/avatar1.png', 
                                'images/avatars/avatar2.png', 
                                'images/avatars/avatar3.png', 
                                'images/avatars/avatar4.png',
                                'images/avatars/avatar5.png'
                            ];
                        
                            foreach ($avatars as $avatar): 
                        ?>
                            <div>
                                <input type="radio" name="profile_character" value="<?php echo $avatar; ?>" id="<?php echo $avatar; ?>" class="hidden" <?php is_checked($user['profile_character_url'], $avatar); ?>>
                                <label for="<?php echo $avatar; ?>" class="cursor-pointer">
                                    <img src="<?php echo $avatar; ?>" alt="Avatar" class="w-20 h-20 rounded-full transition-all duration-200 hover:opacity-80">
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <hr class="my-6">

                <div>
                    <label for="username" class="block text-lg font-semibold text-gray-800 mb-2">Username</label>
                    <p class="text-sm text-gray-500 mb-2">This is the name other users will see in the community.</p>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($user['fullName'] ?? ''); ?>" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400" 
                           placeholder="e.g., HappyMom123" required>
                </div>

                <div>
                    <label for="bio" class="block text-lg font-semibold text-gray-800 mb-2">Your Bio</label>
                    <p class="text-sm text-gray-500 mb-2">Tell the community a little about yourself (optional).</p>
                    <textarea id="bio" name="bio" rows="4" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400" 
                              placeholder="e.g., Expecting my first baby in December. Love to read and cook!"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>

                <div>
                    <button type="submit" class="w-full bg-pink-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>
</html>