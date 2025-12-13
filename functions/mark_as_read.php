<?php
// functions/mark_as_read.php

// 1. Connect to Database
require_once __DIR__ . '/../config/db.php'; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed");
}

// 2. Mark Gas Alerts as Read
// (Assuming you have an is_read column in gas_data)
$sqlGas = "UPDATE gas_data SET is_read = 1 WHERE is_read = 0";
$conn->query($sqlGas);

// 3. Mark Water Alerts as Read
// (Assuming you have an is_read column in water_level)
$sqlWater = "UPDATE water_level SET is_read = 1 WHERE is_read = 0";
$conn->query($sqlWater);

// =========================================================
// 🚀 CRITICAL FIX: DELETE THE CACHE
// =========================================================
// We must delete the cache file so that the next time fetch_notifications.php 
// runs, it is forced to get the NEW data (where is_read = 1) from the database.
$cacheFile = 'notifications_cache.json';

if (file_exists($cacheFile)) {
    unlink($cacheFile); // This deletes the file
}

$conn->close();
?>