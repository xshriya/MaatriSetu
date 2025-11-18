<?php
// 1. Start the session
session_start();

// --- DATABASE CONNECTION ---
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// 2. Get the data from JavaScript
$data = json_decode(file_get_contents('php://input'), true);
$phone = $data['phone'];
$otp = $data['otp'];

// Normalize phone number to match the format used in send_otp.php
$phone = preg_replace('/[^0-9+]/', '', $phone);
if (!str_starts_with($phone, '+')) {
    if (str_starts_with($phone, '91')) {
        $phone = '+' . $phone;
    } elseif (strlen($phone) == 10) {
        $phone = '+91' . $phone;
    }
}

// 3. Check if session variables even exist
if (!isset($_SESSION['otp']) || !isset($_SESSION['phone'])) {
    echo json_encode(['success' => false, 'message' => 'OTP expired or not sent.']);
    exit;
}

// 4. Compare the submitted data to the SESSION data
error_log("DEBUG: Submitted phone: " . $phone);
error_log("DEBUG: Session phone: " . $_SESSION['phone']);
error_log("DEBUG: Submitted OTP: " . $otp);
error_log("DEBUG: Session OTP: " . $_SESSION['otp']);

if ($phone == $_SESSION['phone'] && $otp == $_SESSION['otp']) {
    
    // OTP is correct.
    $_SESSION['user_phone'] = $_SESSION['phone']; 
    unset($_SESSION['otp']); // Clear the OTP

    // Check if user exists in database
    // NOTE: save_details.php stores contactNumber as digits-only, so here
    // we search by both the "+countrycode" format and a digits-only format.
    $phone_digits = preg_replace('/\D/', '', $phone);
    $stmt = $conn->prepare("SELECT id, fullName FROM users WHERE contactNumber = ? OR contactNumber = ?");
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }
    
    $stmt->bind_param("ss", $phone, $phone_digits);
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database error']);
        $stmt->close();
        exit;
    }
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if a row was found AND if fullName is not empty
    if ($row && !empty($row['fullName'])) {
        // USER EXISTS and profile is COMPLETE
        
        // Log them in
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['fullName'];
        
        error_log("User logged in: ID=" . $row['id'] . ", Name=" . $row['fullName']);
        
        // Tell JavaScript the user exists and is logged in
        echo json_encode(['success' => true, 'profile_complete' => true]);

    } else {
        // NEW USER or INCOMPLETE PROFILE
        
        error_log("New user or incomplete profile for phone: " . $phone);
        
        // Tell JavaScript to show the "Complete Your Profile" form
        echo json_encode(['success' => true, 'profile_complete' => false]);
    }
    
    $stmt->close();
    $conn->close();

} else {
    // Failure: Invalid OTP
    error_log("OTP mismatch for phone: " . $phone);
    echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
}
?>