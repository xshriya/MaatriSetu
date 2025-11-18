<?php
session_start();

// 1. Check if user phone is in session
if (!isset($_SESSION['user_phone'])) {
    // If no phone in session, something went wrong. Send them back.
    header('Location: signup.php'); 
    exit;
}

// --- 2. DATABASE CONNECTION ---
require_once 'database.php';
try {
    $conn = getDatabaseConnection();
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
// --- END DATABASE CONNECTION ---

// --- 3. GET ALL DATA FROM FORM & SESSION ---
$phone = $_SESSION['user_phone'];
$phone = preg_replace('/[^0-9]/', '', $phone);
$fullName = $_POST['fullName'];
$age = $_POST['age'];
$address = $_POST['address'];
$pregnancyStage = $_POST['pregnancyStage'];
$languagePref = $_POST['languagePref'];
$familyIncome = $_POST['familyIncome'];
$healthConditions = $_POST['healthConditions'];
$hadMiscarriage = $_POST['miscarriage'];
$relativeName = $_POST['relativeName'];
$relativeRelation = $_POST['relativeRelation'];
$relativePhone = $_POST['relativePhone'];
$is_phone_verified = 1;

// Handle optional/nullable number fields
$weeksPregnant = !empty($_POST['weeksPregnant']) ? $_POST['weeksPregnant'] : NULL;
$previousMiscarriages = !empty($_POST['previousMiscarriages']) ? $_POST['previousMiscarriages'] : NULL;

// --- 4. CHECK IF USER ALREADY EXISTS ---
$stmt = $conn->prepare("SELECT id FROM users WHERE contactNumber = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    // --- 5A. USER EXISTS: UPDATE their record ---
    $sql = "UPDATE users SET 
        fullName = ?, age = ?, address = ?, pregnancyStage = ?, weeksPregnant = ?, 
        languagePref = ?, familyIncome = ?, healthConditions = ?, hadMiscarriage = ?, 
        previousMiscarriages = ?, relativeName = ?, relativeRelation = ?, relativePhone = ?,
        is_phone_verified = ?
        WHERE contactNumber = ?";
    
    // THIS IS THE FIX: 15 types for 15 variables
    $types = "sisisisisssissis"; 
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, 
        $fullName, $age, $address, $pregnancyStage, $weeksPregnant, 
        $languagePref, $familyIncome, $healthConditions, $hadMiscarriage, 
        $previousMiscarriages, $relativeName, $relativeRelation, $relativePhone,
        $is_phone_verified,
        $phone
    );

} else {
    // --- 5B. NEW USER: INSERT a new record ---
    $defaultAvatar = 'images/avatars/avatar1.png';
    $sql = "INSERT INTO users (
        contactNumber, fullName, age, address, pregnancyStage, weeksPregnant, 
        languagePref, familyIncome, healthConditions, hadMiscarriage, 
        previousMiscarriages, relativeName, relativeRelation, relativePhone,
        is_phone_verified, profile_character_url
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // 16 types for 16 variables
    $types = "ssisisiisssissis";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, 
        $phone, $fullName, $age, $address, $pregnancyStage, $weeksPregnant, 
        $languagePref, $familyIncome, $healthConditions, $hadMiscarriage, 
        $previousMiscarriages, $relativeName, $relativeRelation, $relativePhone,
        $is_phone_verified, $defaultAvatar
    );
}

// --- 6. EXECUTE AND LOG IN ---
if ($stmt->execute()) {
    // Success! Log them in
    $_SESSION['user_name'] = $fullName;
    
    if ($row) {
        $_SESSION['user_id'] = $row['id']; // Get ID from the check
    } else {
        $_SESSION['user_id'] = $conn->insert_id; // Get new ID from the INSERT
    }

} else {
    // Failed to save. Log the error.
    error_log("Failed to save details: " . $stmt->error);
}

$stmt->close();
$conn->close();

// --- 7. REDIRECT TO HOME ---
header('Location: index.php');
exit;
?>