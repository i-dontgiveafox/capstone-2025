<?php
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sensor   = isset($_POST['sensor']) ? $_POST['sensor'] : "DHT11";
$location = isset($_POST['location']) ? $_POST['location'] : "Unknown";
$value1   = isset($_POST['value1']) ? $_POST['value1'] : "0";
$value2   = isset($_POST['value2']) ? $_POST['value2'] : "0"; 
$value3   = isset($_POST['value3']) ? $_POST['value3'] : "0"; 

$temp = floatval($value1);

if ($temp >= 31.0) {
    $desc = "Auto-ON: High Temperature detected ({$temp}°C)";
    $event_type = "Exhaust Fan";
    
    $sql_log = "INSERT INTO system_logs (event_type, description, sensor_value) VALUES ('$event_type', '$desc', '$temp')";
    
    $conn->query($sql_log);
}

$sql = "INSERT INTO dht11_data (temp_sensor, temp_location, temp_heat, temp_humid, value3)
        VALUES ('$sensor', '$location', '$value1', '$value2', '$value3')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>