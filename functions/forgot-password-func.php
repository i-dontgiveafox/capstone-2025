<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require '../vendor/autoload.php';
include_once '../config/db_conn.php';

date_default_timezone_set('Asia/Manila');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (isset($_POST['email'])) {
    $input = trim($_POST['email']);

    // Try to find user by username (stored in email column) OR by recovery_email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR recovery_email = ? LIMIT 1");
    $stmt->execute([$input, $input]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Prefer sending to the saved recovery_email
        $sendTo = isset($user['recovery_email']) && !empty($user['recovery_email']) ? $user['recovery_email'] : null;

        if (empty($sendTo)) {
            // No recovery email set for this account
            header("Location: ../public/forgot-password.php?error=" . urlencode('No recovery email is set for this account. Please contact the administrator.'));
            exit;
        }

        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store token by user id (safer than using email string)
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
        $update->execute([$token, $expiry, $user['id']]);

        $reset_link = rtrim($_ENV['APP_URL'], '/') . "/public/reset-password.php?token=" . $token;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];

            $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($sendTo);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
            	
                <p>Click the link below to reset your password:</p>
                <p><a href='" . htmlspecialchars($reset_link) . "'>" . htmlspecialchars($reset_link) . "</a></p>
                <p>This link will expire in 1 hour.</p>
            ";

            $mail->send();
            header("Location: ../public/forgot-password.php?success=" . urlencode('Verification link sent to ' . $sendTo));
            exit;
        } catch (Exception $e) {
            header("Location: ../public/forgot-password.php?error=Failed to send email: " . urlencode($mail->ErrorInfo));
            exit;
        }

    } else {
        header("Location: ../public/forgot-password.php?error=Account not found");
        exit;
    }
} else {
    header("Location: ../public/forgot-password.php");
    exit;
}
?>
