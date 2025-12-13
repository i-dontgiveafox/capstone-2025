<?php
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// --- FIX 1: Set Timezone to UTC (Universal Standard Time) ---
date_default_timezone_set('UTC'); 
// ------------------------------------------------------------

$device = $_GET['device'] ?? '';

if ($device != '') {
  // Called by ESP32 — update heartbeat
  // Use PHP's UTC time variable instead of MySQL's NOW()
  $current_utc = date('Y-m-d H:i:s');

  $stmt = $conn->prepare("UPDATE heartbeat_data SET last_seen = ? WHERE device_name = ?");
  $stmt->bind_param("ss", $current_utc, $device);
  $stmt->execute();

  if ($stmt->affected_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO heartbeat_data (device_name, last_seen) VALUES (?, ?)");
    $stmt->bind_param("ss", $device, $current_utc);
    $stmt->execute();
  }

  echo "OK";
} else {
  // Called by frontend — return status
  $device = "esp32_1"; 
  $query = $conn->prepare("SELECT last_seen FROM heartbeat_data WHERE device_name = ? ORDER BY last_seen DESC LIMIT 1");
  $query->bind_param("s", $device);
  $query->execute();
  $result = $query->get_result();
  $row = $result->fetch_assoc();

  if ($row) {
    // Both of these will now be in UTC, so the math is correct
    $last_seen = strtotime($row['last_seen']);
    $now = time(); 
    
    // Calculate difference
    $diff = $now - $last_seen;

    // Check if difference is small (Online) and positive (Not from future)
    $status = ($diff < 60 && $diff >= -5) ? "online" : "offline";
    
    echo json_encode([
      "status" => $status,
      "last_seen" => $row['last_seen'] // Sends UTC time to your dashboard
    ]);
  } else {
    echo json_encode(["status" => "unknown"]);
  }
}

$conn->close();
?>