<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

// Database connection
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get all reminders for the user, ordered by date and time
$stmt = $conn->prepare("SELECT id, reminder_type, reminder_date, reminder_time, description, notify_relative, is_completed, created_at FROM reminders WHERE user_id = ? ORDER BY reminder_date ASC, reminder_time ASC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$reminders = [];
while ($row = $result->fetch_assoc()) {
    $reminders[] = [
        'id' => (int)$row['id'],
        'type' => $row['reminder_type'],
        'date' => $row['reminder_date'],
        'time' => substr($row['reminder_time'], 0, 5), // Format HH:MM
        'description' => $row['description'],
        'notifyRelative' => (bool)$row['notify_relative'],
        'isCompleted' => (bool)$row['is_completed'],
        'created' => $row['created_at']
    ];
}

echo json_encode(['success' => true, 'reminders' => $reminders]);

$stmt->close();
$conn->close();