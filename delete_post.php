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
$post_id = isset($data['post_id']) ? (int)$data['post_id'] : 0;

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

// First, verify that the post belongs to the current user
$stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    echo json_encode(['success' => false, 'message' => 'Post not found']);
    $stmt->close();
    $conn->close();
    ob_end_flush();
    exit;
}

if ($post['user_id'] != $user_id) {
    echo json_encode(['success' => false, 'message' => 'You can only delete your own posts']);
    $stmt->close();
    $conn->close();
    ob_end_flush();
    exit;
}

// Delete related likes first (foreign key constraint)
$stmt = $conn->prepare("DELETE FROM post_likes WHERE post_id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();

// Now delete the post
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Post not found or already deleted']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();

// Clean output buffer and flush response
ob_end_flush();
?>
