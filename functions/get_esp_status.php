<?php
header('Content-Type: application/json');

date_default_timezone_set('Asia/Manila');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  echo json_encode(["error" => "DB connection failed"]);
  exit;
}

$device = 'esp32_1'; 
$timeout = 30;

$sql = "SELECT last_seen FROM devices WHERE device_name = '$device'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $last_seen = strtotime($row['last_seen']);
  $status = (time() - $last_seen <= $timeout) ? 'online' : 'offline';

  echo json_encode([
    "device" => $device,
    "status" => $status,
    "last_seen" => date("Y-m-d H:i:s", $last_seen)
  ]);
} else {
  echo json_encode(["device" => $device, "status" => "unknown"]);
}

$conn->close();
?>
