<?php
header('Content-Type: application/json; charset=utf-8'); // ensure JSON header

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

date_default_timezone_set('Asia/Manila');

$device = "esp32_1"; // your device name
$query = $conn->prepare("SELECT last_seen FROM heartbeat_data WHERE device_name = ? ORDER BY last_seen DESC LIMIT 1");
$query->bind_param("s", $device);
$query->execute();
$result = $query->get_result();

if ($result === false) {
    echo json_encode(["status" => "error", "message" => $conn->error]);
    exit;
}

$row = $result->fetch_assoc();

if ($row) {
    $last_seen = strtotime($row['last_seen']);
    $now = time();
    $threshold = 10; 
  $status = ($now - $last_seen) < $threshold ? "online" : "offline";


    echo json_encode([
        "status" => $status,
        "last_seen" => $row['last_seen']
    ]);
} else {
    echo json_encode(["status" => "unknown", "message" => "Device not found"]);
}

$conn->close();
?>
