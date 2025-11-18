<?php
require_once __DIR__ . '/../config/db.php';


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Temperature & Humidity (dht11_data)
$temp_sql = "SELECT temp_heat, temp_humid, temp_timestamp FROM dht11_data ORDER BY temp_timestamp DESC LIMIT 1";
$temp_result = $conn->query($temp_sql);
if ($temp_result->num_rows > 0) {
    $row = $temp_result->fetch_assoc();
    $temp_value = $row['temp_heat'];
    $humid_value = $row['temp_humid'];
    $temp_last = $row['temp_timestamp'];
} else {
    $temp_value = "--";
    $humid_value = "--";
    $temp_last = "--";
}

// Moisture (moisture_data)
$moist_sql = "SELECT moisture_level, moisture_timestamp FROM moisture_data ORDER BY moisture_timestamp DESC LIMIT 1";
$moist_result = $conn->query($moist_sql);
if ($moist_result->num_rows > 0) {
    $row = $moist_result->fetch_assoc();
    $moist_value = $row['moisture_level'];
    $moist_last = $row['moisture_timestamp'];
} else {
    $moist_value = "--";
    $moist_last = "--";
}

// Methane (gas_data)
$gas_sql = "SELECT gas_percent, gas_timestamp FROM gas_data ORDER BY gas_timestamp DESC LIMIT 1";
$gas_result = $conn->query($gas_sql);
if ($gas_result->num_rows > 0) {
    $row = $gas_result->fetch_assoc();
    $gas_value = $row['gas_percent'];
    $gas_last = $row['gas_timestamp'];
} else {
    $gas_value = "--";
    $gas_last = "--";
}

$conn->close();

// Return as JSON
echo json_encode([
    'temperature' => ['value' => $temp_value, 'last' => $temp_last],
    'humidity' => ['value' => $humid_value, 'last' => $temp_last],
    'moisture' => ['value' => $moist_value, 'last' => $moist_last],
    'methane' => ['value' => $gas_value, 'last' => $gas_last]
]);
?>