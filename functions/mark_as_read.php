<?php
// functions/mark_as_read.php

// 1. Connect to Database
require_once __DIR__ . '/../config/db.php'; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed");
}

// 2. Mark Gas Alerts as Read
$sqlGas = "UPDATE gas_data SET is_read = 1 WHERE is_read = 0";
$conn->query($sqlGas);

// 3. Mark Water Alerts as Read
$sqlWater = "UPDATE water_level SET is_read = 1 WHERE is_read = 0";
$conn->query($sqlWater);

// =========================================================
// 4. (NEW) Mark Temp/Fan Alerts as Read
// =========================================================
// This is the missing part! 
// This clears the "High Temperature" and "Fan" alerts.
$sqlNotif = "UPDATE notifications SET is_read = 1 WHERE is_read = 0";
$conn->query($sqlNotif);

// =========================================================
// 5. DELETE THE CACHE (CRITICAL)
// =========================================================
$cacheFile = 'notifications_cache.json';

if (file_exists($cacheFile)) {
    unlink($cacheFile); // Delete file so website fetches fresh data immediately
}

$conn->close();
?>