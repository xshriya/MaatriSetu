<?php
// Start output buffering to prevent any accidental output
ob_start();
session_start();

// Set error reporting (but don't display errors to avoid breaking JSON)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$content = isset($data['content']) ? trim($data['content']) : '';

if (empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Post content cannot be empty']);
    exit;
}

// Database connection
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Insert post
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Get user avatar
$stmt = $conn->prepare("SELECT profile_character_url FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$user_avatar = !empty($user_data['profile_character_url']) ? $user_data['profile_character_url'] : 'https://cdn-icons-png.flaticon.com/512/4140/4140047.png';

$stmt = $conn->prepare("INSERT INTO posts (user_id, user_name, content) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $user_name, $content);

if ($stmt->execute()) {
    $post_id = $conn->insert_id;
    if ($post_id > 0) {
        echo json_encode([
            'success' => true,
            'post' => [
                'id' => $post_id,
                'user_name' => $user_name,
                'user_avatar' => $user_avatar,
                'content' => $content,
                'likes' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'user_reaction' => 'none'
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Post created but failed to get ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();

// Clean output buffer and flush response
ob_end_flush();
?>