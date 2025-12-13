<?php
// send_alert_email.php
// Simple wrapper to send alert emails using existing PHPMailer setup

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_alert_email($toEmail, $subject, $bodyHtml, $alert_type = null) {
    // Load .env if present (optional)
    if (file_exists(__DIR__ . '/../.env')) {
        $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                // Remove surrounding quotes if present
                if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                    (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                    $value = substr($value, 1, -1);
                }
                putenv("$key=$value");
            }
        }
    }

    $mailHost = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
    $mailPort = getenv('MAIL_PORT') ?: 587;
    $mailUser = getenv('MAIL_USERNAME') ?: '';
    $mailPass = getenv('MAIL_PASSWORD') ?: '';
    $mailFrom = getenv('MAIL_FROM') ?: 'no-reply@vermicast.com';
    $mailFromName = getenv('MAIL_FROM_NAME') ?: 'VermiCare';

    $mail = new PHPMailer(true);
  
  
  
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $mailHost;
        $mail->SMTPAuth = true;
        $mail->Username = $mailUser;
        $mail->Password = $mailPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $mailPort;

        // Recipients
        $mail->setFrom($mailFrom, $mailFromName);
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyHtml;

        $mail->send();
        // Log audit (if DB available)
        try {
            $dbHost = getenv('DB_HOST') ?: 'localhost';
            $dbUser = getenv('DB_USER') ?: 'root';
            $dbPass = getenv('DB_PASS') ?: '';
            $dbName = getenv('DB_NAME') ?: '';
            $connLog = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
            if (!$connLog->connect_error) {
                $stmt = $connLog->prepare('INSERT INTO email_audit (alert_type, recipient, subject, body) VALUES (?, ?, ?, ?)');
                $atype = $alert_type;
                $stmt->bind_param('ssss', $atype, $toEmail, $subject, $bodyHtml);
                $stmt->execute();
                $stmt->close();
                $connLog->close();
            }
        } catch (Exception $e) {
            // ignore audit errors
        }
        return true;
    } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
