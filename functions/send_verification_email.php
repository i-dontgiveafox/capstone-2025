<?php
// Set JSON header immediately to prevent any output issues
header('Content-Type: application/json');

// Only process AJAX POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if ($action !== 'send_verification') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load environment variables
$env = [];
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env', true);
    if (is_array($env) && isset($env[0])) {
        $env = $env[0]; // In case ini_file returns nested array
    }
}

// Generate 6-digit verification code
$verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

// Store code in session (expires in 15 minutes)
$_SESSION['email_verification_code'] = $verificationCode;
$_SESSION['email_verification_code_time'] = time();
$_SESSION['email_verification_email'] = $email;

// Get user name
$userName = 'User';
try {
    $stmt = $conn->prepare('SELECT first_name, last_name FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $userName = $user['first_name'] . ' ' . $user['last_name'];
    }
} catch (Exception $e) {
    // Continue with default name
}

// Send email via PHPMailer
try {
    $mail = new PHPMailer(true);
    
    // SMTP Configuration from .env
    $mail->isSMTP();
    $mail->Host = $env['MAIL_HOST'] ?? 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = trim($env['MAIL_USERNAME'] ?? 'vermicast2025@gmail.com', '\'"');
    $mail->Password = trim($env['MAIL_PASSWORD'] ?? '', '\'"');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int)($env['MAIL_PORT'] ?? 587);
    
    // Email details
    $fromEmail = trim($env['MAIL_FROM'] ?? 'vermicast2025@gmail.com', '\'"');
    $fromName = trim($env['MAIL_FROM_NAME'] ?? 'VermiCast', '\'"');
    
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'VermiCare - Email Verification Code';
    
    $htmlBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
            .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .header { text-align: center; color: #1e1e1e; margin-bottom: 20px; }
            .code-box { background-color: #CCEBD5; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0; }
            .code { font-size: 32px; font-weight: bold; letter-spacing: 2px; color: #1e1e1e; }
            .footer { color: #666; font-size: 12px; text-align: center; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Email Verification</h1>
                <p>Hello <strong>$userName</strong>,</p>
            </div>
            
            <p>You requested to add a recovery email to your VermiCare account. Please use the verification code below to confirm your email address:</p>
            
            <div class='code-box'>
                <div class='code'>$verificationCode</div>
            </div>
            
            <p>This code will expire in 15 minutes.</p>
            
            <p>If you did not request this, please ignore this email.</p>
            
            <div class='footer'>
                <p>© 2025 VermiCare. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $mail->Body = $htmlBody;
    $mail->AltBody = "Your VermiCare verification code is: $verificationCode\n\nThis code will expire in 15 minutes.";
    
    $mail->send();
    
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Verification email sent successfully.']);
    exit;
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()]);
    exit;
}

?>
