<?php
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

if (isset($_GET['moisture'])) {
    $moisture = intval($_GET['moisture']);

    date_default_timezone_set('Asia/Manila');
    $timestamp = date('Y-m-d H:i:s');

    if ($moisture <= 55) {
        $desc = "Auto-ON: Low Moisture detected ({$moisture}%)";
        $event_type = "Water Pump";
        

        $sql_log = "INSERT INTO system_logs (event_type, description, sensor_value) VALUES ('$event_type', '$desc', '$moisture')";
        $conn->query($sql_log);
    }
    
    $sql = "INSERT INTO moisture_data (moisture_level, moisture_timestamp) VALUES ($moisture, '$timestamp')";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Data inserted at $timestamp";
    } else {
        echo "❌ Error: " . $conn->error;
    }
} else {
    echo "⚠️ No data received";
}

$conn->close();
?>