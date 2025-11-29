<?php
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) { die("Connection failed"); }

// Update all Unread Gas Logs to Read (1)
$sql1 = "UPDATE gas_data SET is_read = 1 WHERE is_read = 0";
$conn->query($sql1);

// Update all Unread Water Logs to Read (1)
$sql2 = "UPDATE water_level SET is_read = 1 WHERE is_read = 0";
$conn->query($sql2);

$conn->close();
?>