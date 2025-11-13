<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  echo json_encode(["error" => $conn->connect_error]);
  exit();
}

// Group today's readings by hour (based on actual timestamps)
$query = "
SELECT 
  DATE_FORMAT(moisture_timestamp, '%H:00') AS hour_label,
  ROUND(AVG(moisture_level), 2) AS avg_moisture
FROM moisture_data
WHERE DATE(moisture_timestamp) = CURDATE()
GROUP BY HOUR(moisture_timestamp)
ORDER BY HOUR(moisture_timestamp);
";

$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = [
    "hour" => $row['hour_label'], 
    "avg_moisture" => (float)$row['avg_moisture']
  ];
}

echo json_encode($data);
$conn->close();
?>
