<?php
include_once '../config/db_conn.php';

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $token = bin2hex(random_bytes(50)); 
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        $update->execute([$token, $expiry, $email]);
        if ($update->rowCount() == 0) {

        }

        $reset_link = "http://localhost/project/public/reset-password.php?token=" . urlencode($token);
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n$reset_link\n\nThis link will expire in 1 hour.";
        $headers = "From: vermiCare@no-reply.com";

        if (mail($email, $subject, $message, $headers)) {
            header("Location: ../public/forgot-password.php?success=Password reset link sent to your email");
            exit;
        } else {
            header("Location: ../public/forgot-password.php?error=Failed to send email");
            exit;
        }
    } else {
        header("Location: ../public/forgot-password.php?error=Email not found");
        exit;
    }
} else {
    header("Location: ../public/forgot-password.php");
    exit;
}
?>
