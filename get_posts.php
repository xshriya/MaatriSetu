<?php
session_start();
header('Content-Type: application/json');

// Database connection
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Get posts with user's reaction and avatar
$sql = "SELECT p.id, p.user_id, p.user_name, p.content, p.likes, p.created_at,
        u.profile_character_url,
        (SELECT is_like FROM post_likes WHERE post_id = p.id AND user_id = ?) as user_reaction
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
        LIMIT 50";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $user_reaction = 'none';
    if ($row['user_reaction'] !== null) {
        $user_reaction = $row['user_reaction'] == 1 ? 'like' : 'dislike';
    }
    
    $avatar = !empty($row['profile_character_url']) ? $row['profile_character_url'] : 'https://cdn-icons-png.flaticon.com/512/4140/4140047.png';
    
    $posts[] = [
        'id' => $row['id'],
        'user_id' => $row['user_id'],
        'user_name' => $row['user_name'],
        'user_avatar' => $avatar,
        'content' => $row['content'],
        'likes' => (int)$row['likes'],
        'created_at' => $row['created_at'],
        'user_reaction' => $user_reaction
    ];
}

echo json_encode(['success' => true, 'posts' => $posts]);

$stmt->close();
$conn->close();
?>