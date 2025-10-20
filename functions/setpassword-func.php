<?php
session_start();
require_once '../config/db_conn.php';

if (!isset($_SESSION['temp_email']) || !isset($_POST['password'])) {
    header("Location: ../public/login.php?error=Invalid access");
    exit();
}

$first_name = $_SESSION['temp_first_name'];
$last_name  = $_SESSION['temp_last_name'];
$email = $_SESSION['temp_email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

try {
    $insert = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $insert->execute([$first_name, $last_name, $email, $password]);

    // Save real login session
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;
    $_SESSION['email'] = $email;
    $_SESSION['user_id'] = $conn->lastInsertId();

    // Remove temporary session data after password creation
    unset($_SESSION['temp_first_name']);
    unset($_SESSION['temp_last_name']);
    unset($_SESSION['temp_email']);

    header("Location: ../public/index.php");
    exit();
} catch (PDOException $e) {
    header("Location: ../public/login.php?error=" . urlencode("Error creating account"));
    exit();
}
