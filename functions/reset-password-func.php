<?php
include_once '../config/db_conn.php';

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Always generate a new token
        $token = bin2hex(random_bytes(50)); 
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Force update token + expiry every time
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        $update->execute([$token, $expiry, $email]);

        // Confirm it saved (for debugging, you can remove later)
        if ($update->rowCount() == 0) {
            // In some cases (same values), rowCount() might be 0 even though update succeeded.
            // So we just continue.
        }

        // Reset link
        $reset_link = "http://localhost/project/public/reset-password.php?token=" . urlencode($token);

        // Send email
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n$reset_link\n\nThis link will expire in 1 hour.";
        $headers = "From: no-reply@yourdomain.com";

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
