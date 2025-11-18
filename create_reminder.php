<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($data['type']) || empty($data['date']) || empty($data['time'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$userId = $_SESSION['user_id'];
$type = trim($data['type']);
$date = trim($data['date']);
$time = trim($data['time']);
$description = isset($data['description']) ? trim($data['description']) : '';
$notifyRelative = isset($data['notifyRelative']) ? (bool)$data['notifyRelative'] : false;

// Database connection
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Insert reminder
$stmt = $conn->prepare("INSERT INTO reminders (user_id, reminder_type, reminder_date, reminder_time, description, notify_relative) VALUES (?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("issssi", $userId, $type, $date, $time, $description, $notifyRelative);

if ($stmt->execute()) {
    $reminderId = $conn->insert_id();
    $formattedTime = $time;
    if (strlen($time) > 5) {
        $formattedTime = substr($time, 0, 5);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Reminder created successfully',
        'reminder' => [
            'id' => (int)$reminderId,
            'type' => $type,
            'date' => $date,
            'time' => $formattedTime,
            'description' => $description,
            'notifyRelative' => (bool)$notifyRelative,
            'isCompleted' => false,
            'created' => date('Y-m-d H:i:s')
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();