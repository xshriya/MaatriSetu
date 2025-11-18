<?php
// Start the session to store the OTP
session_start();

// Load SMS Configuration
$SMS_CONFIG = require_once 'sms_config.php';

// Get the phone number from the JavaScript fetch request
$data = json_decode(file_get_contents('php://input'), true);
$phone = $data['phone'];

// Basic validation
if (!$phone) {
    echo json_encode(['success' => false, 'message' => 'Phone number is required.']);
    exit;
}

// Normalize phone number (ensure it starts with +91 for India)
$phone = preg_replace('/[^0-9+]/', '', $phone);
if (!str_starts_with($phone, '+')) {
    if (str_starts_with($phone, '91')) {
        $phone = '+' . $phone;
    } elseif (strlen($phone) == 10) {
        $phone = '+91' . $phone;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid phone number format.']);
        exit;
    }
}

// Generate a 6-digit random OTP
$otp = rand(100000, 999999);

// Store the OTP and phone number in the session for verification
$_SESSION['otp'] = $otp;
$_SESSION['phone'] = $phone;

// Send SMS based on configured service
$sms_sent = false;
$error_message = '';

switch ($SMS_CONFIG['service']) {
    case 'twilio':
        $sms_sent = sendTwilioSMS($phone, $otp, $SMS_CONFIG['twilio'], $error_message);
        break;
    case 'textlocal':
        $sms_sent = sendTextLocalSMS($phone, $otp, $SMS_CONFIG['textlocal'], $error_message);
        break;
    case 'fast2sms':
        $sms_sent = sendFast2SMS($phone, $otp, $SMS_CONFIG['fast2sms'], $error_message);
        break;
    default:
        // Fallback to error log for testing
        error_log("OTP for " . $phone . " is " . $otp);
        $sms_sent = true;
}

if ($sms_sent) {
    echo json_encode(['success' => true, 'message' => 'OTP sent successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send OTP: ' . $error_message]);
}

// Twilio SMS Function
function sendTwilioSMS($phone, $otp, $config, &$error_message) {
    $account_sid = $config['account_sid'];
    $auth_token = $config['auth_token'];
    $from_number = $config['from_number'];
    
    $message = "Your MaatriSetu verification code is: $otp. Do not share this code with anyone.";
    
    $url = "https://api.twilio.com/2010-04-01/Accounts/$account_sid/Messages.json";
    
    $data = [
        'From' => $from_number,
        'To' => $phone,
        'Body' => $message
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$account_sid:$auth_token");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 201) {
        return true;
    } else {
        $error_message = "Twilio API error: HTTP $http_code - $response";
        error_log($error_message);
        return false;
    }
}

// TextLocal SMS Function (India-specific)
function sendTextLocalSMS($phone, $otp, $config, &$error_message) {
    $api_key = $config['api_key'];
    $sender = $config['sender'];
    
    $message = "Your MaatriSetu verification code is: $otp. Do not share this code with anyone.";
    
    $url = "https://api.textlocal.in/send/";
    
    $data = [
        'apikey' => $api_key,
        'numbers' => $phone,
        'message' => $message,
        'sender' => $sender
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($http_code == 200 && isset($result['status']) && $result['status'] == 'success') {
        return true;
    } else {
        $error_message = "TextLocal API error: " . ($result['errors'][0]['message'] ?? $response);
        error_log($error_message);
        return false;
    }
}

// Fast2SMS Function (India-specific)
function sendFast2SMS($phone, $otp, $config, &$error_message) {
    $api_key = $config['api_key'];
    $sender_id = $config['sender_id'];
    
    $message = "Your MaatriSetu verification code is: $otp. Do not share this code with anyone.";
    
    $url = "https://www.fast2sms.com/dev/bulkV2";
    
    $data = [
        'authorization' => $api_key,
        'sender_id' => $sender_id,
        'message' => $message,
        'numbers' => str_replace('+91', '', $phone), // Fast2SMS expects numbers without +91
        'route' => 'v3'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'authorization: ' . $api_key,
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($http_code == 200 && isset($result['return']) && $result['return'] == true) {
        return true;
    } else {
        $error_message = "Fast2SMS API error: " . ($result['message'] ?? $response);
        error_log($error_message);
        return false;
    }
}
?>