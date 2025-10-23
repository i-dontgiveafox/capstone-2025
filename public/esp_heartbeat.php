<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$device = $_GET['device'] ?? '';

if ($device != '') {
  $stmt = $conn->prepare("UPDATE devices SET last_seen = NOW() WHERE device_name = ?");
  $stmt->bind_param("s", $device);
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO devices (device_name, last_seen) VALUES (?, NOW())");
    $stmt->bind_param("s", $device);
    $stmt->execute();
  }

  echo "OK";
}
$conn->close();
?>
