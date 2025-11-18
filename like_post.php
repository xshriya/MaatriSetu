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
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$post_id = isset($data['post_id']) ? (int)$data['post_id'] : 0;
$action = isset($data['action']) ? $data['action'] : 'like'; // 'like' or 'dislike'

if ($post_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
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

$user_id = $_SESSION['user_id'];

// Check if user already reacted to this post
$stmt = $conn->prepare("SELECT id, is_like FROM post_likes WHERE post_id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$existing = $result->fetch_assoc();

if ($existing) {
    // User already reacted
    $current_is_like = (bool)$existing['is_like'];
    
    if (($action === 'like' && $current_is_like) || ($action === 'dislike' && !$current_is_like)) {
        // Remove reaction (unlike/undislike)
        $stmt = $conn->prepare("DELETE FROM post_likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        
        // Adjust count
        if ($current_is_like) {
            $stmt = $conn->prepare("UPDATE posts SET likes = likes - 1 WHERE id = ?");
        } else {
            $stmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
        }
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        
        $user_reaction = 'none';
    } else {
        // Change reaction (like to dislike or dislike to like)
        $new_is_like = ($action === 'like') ? 1 : 0;
        $stmt = $conn->prepare("UPDATE post_likes SET is_like = ? WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("iii", $new_is_like, $post_id, $user_id);
        $stmt->execute();
        
        // Adjust count (change by 2: remove old, add new)
        if ($action === 'like') {
            $stmt = $conn->prepare("UPDATE posts SET likes = likes + 2 WHERE id = ?");
        } else {
            $stmt = $conn->prepare("UPDATE posts SET likes = likes - 2 WHERE id = ?");
        }
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        
        $user_reaction = $action;
    }
} else {
    // New reaction
    $is_like = ($action === 'like') ? 1 : 0;
    $stmt = $conn->prepare("INSERT INTO post_likes (post_id, user_id, is_like) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $post_id, $user_id, $is_like);
    $stmt->execute();
    
    // Adjust count
    if ($action === 'like') {
        $stmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
    } else {
        $stmt = $conn->prepare("UPDATE posts SET likes = likes - 1 WHERE id = ?");
    }
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    
    $user_reaction = $action;
}

// Get updated like count
$stmt = $conn->prepare("SELECT likes FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode([
    'success' => true,
    'user_reaction' => $user_reaction,
    'likes' => (int)$row['likes']
]);

$stmt->close();
$conn->close();

// Clean output buffer and flush response
ob_end_flush();
?>