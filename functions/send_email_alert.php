<?php
// functions/send_email_alert.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(sprintf('%s=%s', trim($name), trim(trim($value), '"\'')));
    }
}
loadEnv(__DIR__ . '/../.env');

function sendEmailAlert($type, $value) {

    // --- 1. SPAM PROTECTION (30 Minutes) ---
    // This prevents the system from spamming you every few seconds.
    $cooldownFile = __DIR__ . "/email_cooldown_{$type}.txt";
    $cooldownTime = 1800; // 1800 seconds = 30 Minutes

    if (file_exists($cooldownFile)) {
        $lastSent = file_get_contents($cooldownFile);
        if (time() - $lastSent < $cooldownTime) {
            return; // Too soon! Stop here.
        }
    }

    // --- 2. GET RECIPIENT (ID = 8) ---
    $recipientEmail = '';
    $recipientName = 'Admin';
    
    // Ensure you use the correct file name (db.php or db_conn.php)
    require __DIR__ . '/../config/db.php'; 
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if (!$conn->connect_error) {
        // We look for ID 8 specifically
        $sql = "SELECT first_name, email, recovery_email FROM users WHERE id = 8 LIMIT 1";
        $result = $conn->query($sql);
        if ($row = $result->fetch_assoc()) {
            $recipientName = $row['first_name'];
            $recipientEmail = !empty($row['recovery_email']) ? $row['recovery_email'] : $row['email'];
        }
        $conn->close();
    }

    if (empty($recipientEmail)) {
        return; // Silent fail if no user found
    }

    // --- 3. SEND EMAIL ---
    $mail = new PHPMailer(true);
    
    try {
        // Production Settings (Debug OFF)
        $mail->isSMTP();
        $mail->Host       = getenv('MAIL_HOST');
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('MAIL_USERNAME');
        $mail->Password   = getenv('MAIL_PASSWORD'); 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = getenv('MAIL_PORT');

        $mail->setFrom(getenv('MAIL_USERNAME'), getenv('MAIL_FROM_NAME'));
        $mail->addAddress($recipientEmail, $recipientName); 

        $mail->isHTML(true);
        
        // Timezone for email content
        date_default_timezone_set('Asia/Manila');
        $time = date("h:i A");

        $subject = "";
        $body = "";

        if ($type == 'ammonia') {
            $subject = "VermiCare Alert: Harvest Ready";
            $body = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                    <h2 style='color: #d32f2f;'>Vermicast Ready to Harvest!</h2>
                    <p>Hello <b>{$recipientName}</b>,</p>
                    <p>The system has detected high ammonia levels. This indicates your Vermicast is ready.</p>
                    <ul style='background: #fff5f5; padding: 15px; border-radius: 5px;'>
                        <li><b>Current Level:</b> {$value}%</li>
                        <li><b>Time:</b> {$time}</li>
                        <li><b>Status:</b> <span style='color: red; font-weight: bold;'>ACTION REQUIRED</span></li>
                    </ul>
                    <p>Please check the worm bin immediately.</p>
                </div>";
        } elseif ($type == 'water') {
            $subject = "VermiCare Alert: Low Water Level";
            $body = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                    <h2 style='color: #0288d1;'>Water Tank is Low</h2>
                    <p>Hello <b>{$recipientName}</b>,</p>
                    <p>The water sensor indicates that the tank needs refilling.</p>
                    <ul style='background: #e1f5fe; padding: 15px; border-radius: 5px;'>
                        <li><b>Status:</b> LOW</li>
                        <li><b>Time:</b> {$time}</li>
                    </ul>
                    <p>The misting system will not function correctly until refilled.</p>
                </div>";
        }

        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();

        // Update cooldown timestamp
        file_put_contents($cooldownFile, time());

    } catch (Exception $e) {
        // Silent error in production (so ESP32 doesn't crash)
        // error_log($mail->ErrorInfo); 
    }
}
?>