<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch latest 20 readings for moisture trend
$sql = "SELECT moisture_level, moisture_timestamp 
        FROM moisture_data 
        ORDER BY moisture_id DESC 
        LIMIT 20";

$result = $conn->query($sql);
$data = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = [
      "timestamp" => $row['moisture_timestamp'],
      "moisture" => (float)$row['moisture_level']
    ];
  }
}

echo json_encode(array_reverse($data)); // reverse for oldest → newest
$conn->close();
?>
