<?php
session_start();

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

// 2. Get data from frontend
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['mood'])) {
    echo json_encode(['success' => false, 'message' => 'No mood selected.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$mood = $data['mood'];
$today_date = date('Y-m-d'); // Get current server date

// 3. Database Connection
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// 4. SQL: Insert a new mood or update it if one for today already exists
$sql = "INSERT INTO mood_log (user_id, log_date, mood) 
        VALUES (?, ?, ?) 
        ON DUPLICATE KEY UPDATE mood = ?";

$stmt = $conn->prepare($sql);
// We bind $mood twice, once for INSERT, once for UPDATE
$stmt->bind_param("isss", $user_id, $today_date, $mood, $mood);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'new_mood' => $mood]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save mood.']);
}

$stmt->close();
$conn->close();
?>