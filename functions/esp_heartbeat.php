<?php
$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

date_default_timezone_set('Asia/Manila');

$device = $_GET['device'] ?? '';

if ($device != '') {
  // Called by ESP32 — update heartbeat
  $stmt = $conn->prepare("UPDATE heartbeat_data SET last_seen = NOW() WHERE device_name = ?");
  $stmt->bind_param("s", $device);
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO heartbeat_data (device_name, last_seen) VALUES (?, NOW())");
    $stmt->bind_param("s", $device);
    $stmt->execute();
  }

  echo "OK";
} else {
  // Called by frontend — return status
  $device = "esp32_1"; // specify your device name here
  $query = $conn->prepare("SELECT last_seen FROM heartbeat_data WHERE device_name = ? ORDER BY last_seen DESC LIMIT 1");
  $query->bind_param("s", $device);
  $query->execute();
  $result = $query->get_result();
  $row = $result->fetch_assoc();

  if ($row) {
    $last_seen = strtotime($row['last_seen']);
    $now = time();
    $status = ($now - $last_seen) < 60 ? "online" : "offline";
    echo json_encode([
      "status" => $status,
      "last_seen" => $row['last_seen']
    ]);
  } else {
    echo json_encode(["status" => "unknown"]);
  }
}

$conn->close();
?>
