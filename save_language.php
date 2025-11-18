<?php
session_start();
header('Content-Type: application/json');

// Get language from POST request
$data = json_decode(file_get_contents('php://input'), true);
$language = isset($data['language']) ? trim($data['language']) : '';

// Validate language code
$allowed_languages = ['en', 'hi', 'ta', 'te', 'bn', 'mr', 'gu', 'kn', 'ml', 'pa'];
if (!in_array($language, $allowed_languages)) {
    echo json_encode(['success' => false, 'message' => 'Invalid language code']);
    exit;
}

// Save to session for all users
$_SESSION['language'] = $language;

// If user is logged in, also save to database
if (isset($_SESSION['user_id'])) {
    require_once 'database.php';
    try {
        $conn = getDatabaseConnection();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    
    // Update user's language preference using correct column name
    $stmt = $conn->prepare("UPDATE users SET LanguagePref = ? WHERE id = ?");
    $stmt->bind_param("si", $language, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Language preference saved', 'language' => $language]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save language preference']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    // For non-logged-in users, just confirm session save
    echo json_encode(['success' => true, 'message' => 'Language saved to session', 'language' => $language]);
}