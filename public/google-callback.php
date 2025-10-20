<?php
session_start();
require_once '../functions/google-func.php';
require_once '../config/db_conn.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $google_service = new Google_Service_Oauth2($client);
        $google_user = $google_service->userinfo->get();

        // ✅ Get Google data
        $first_name = $google_user['given_name'];
        $last_name  = $google_user['family_name']; // ← last name here
        $email      = $google_user['email'];
        $picture    = $google_user['picture'];

        // Check if user already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            // Existing user → log in
            $user = $stmt->fetch();
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name']  = $user['last_name'];
            $_SESSION['email']      = $user['email'];
            $_SESSION['user_id']    = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            // New Google user → redirect to password setup (Temporary session but will remove after creating password)
            $_SESSION['temp_first_name'] = $first_name;
            $_SESSION['temp_last_name']  = $last_name;   // ← save it temporarily
            $_SESSION['temp_email']      = $email;
            $_SESSION['temp_picture']    = $picture;
            header("Location: set-password.php");
            exit();
        }
    } else {
        header("Location: login.php?error=Google login failed");
        exit();
    }
} else {
    header("Location: login.php?error=No authorization code");
    exit();
}
