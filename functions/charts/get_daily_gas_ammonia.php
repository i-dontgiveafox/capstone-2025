<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/db.php';


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// --- Get last 20 gas readings ---
$gas_sql = "
  SELECT gas_percent, gas_timestamp 
  FROM gas_data 
  ORDER BY gas_id DESC 
  LIMIT 20
";
$gas_result = $conn->query($gas_sql);
$gas_data = [];

if ($gas_result->num_rows > 0) {
  while ($row = $gas_result->fetch_assoc()) {
    $gas_data[] = [
      'timestamp' => $row['gas_timestamp'],
      'gas_percent' => (float)$row['gas_percent']
    ];
  }
}

// --- Get last 20 ammonia readings ---
$ammonia_sql = "
  SELECT ammonia_value, timestamp 
  FROM ammonia_readings 
  ORDER BY id DESC 
  LIMIT 20
";
$ammonia_result = $conn->query($ammonia_sql);
$ammonia_data = [];

if ($ammonia_result->num_rows > 0) {
  while ($row = $ammonia_result->fetch_assoc()) {
    $ammonia_data[] = [
      'timestamp' => $row['timestamp'],
      'ammonia_value' => (float)$row['ammonia_value']
    ];
  }
}

// Combine and return both
echo json_encode([
  'gas' => array_reverse($gas_data),
  'ammonia' => array_reverse($ammonia_data)
]);

$conn->close();
?>
