<?php


header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$code = isset($_POST['code']) ? trim($_POST['code']) : '';

// Check if verification code session exists
if (!isset($_SESSION['email_verification_code']) || !isset($_SESSION['email_verification_code_time'])) {
    echo json_encode(['success' => false, 'message' => 'No verification code sent. Please request a new one.']);
    exit;
}

// Check if code expired (15 minutes = 900 seconds)
$timeElapsed = time() - $_SESSION['email_verification_code_time'];
if ($timeElapsed > 900) {
    unset($_SESSION['email_verification_code']);
    unset($_SESSION['email_verification_code_time']);
    unset($_SESSION['email_verification_email']);
    echo json_encode(['success' => false, 'message' => 'Verification code expired. Please request a new one.']);
    exit;
}

// Verify code matches
if ($code !== $_SESSION['email_verification_code']) {
    echo json_encode(['success' => false, 'message' => 'Invalid verification code. Please try again.']);
    exit;
}

// Code is valid - update database with recovery email
require_once __DIR__ . '/../config/db_conn.php';

$recoveryEmail = $_SESSION['email_verification_email'];
$userId = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare('UPDATE users SET recovery_email = ? WHERE id = ?');
    $stmt->execute([$recoveryEmail, $userId]);
    
    // Clear verification session data
    unset($_SESSION['email_verification_code']);
    unset($_SESSION['email_verification_code_time']);
    unset($_SESSION['email_verification_email']);
    
    echo json_encode(['success' => true, 'message' => "Email verified and saved successfully."]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error saving recovery email. Please try again.']);
}

?>
