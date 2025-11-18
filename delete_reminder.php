<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Reminder ID is required']);
    exit;
}

$userId = $_SESSION['user_id'];
$reminderId = (int)$data['id'];

// Database connection
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Delete reminder (only if it belongs to the user)
$stmt = $conn->prepare("DELETE FROM reminders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $reminderId, $userId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Reminder deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Reminder not found or already deleted']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete reminder']);
}

$stmt->close();
$conn->close();