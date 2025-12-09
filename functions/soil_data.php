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
        
        // Use prepared statement for logging
        $stmt_log = $conn->prepare("INSERT INTO system_logs (event_type, description, sensor_value) VALUES (?, ?, ?)");
        $stmt_log->bind_param("ssi", $event_type, $desc, $moisture);
        $stmt_log->execute();
        $stmt_log->close();
    }
    
    // Use prepared statement for moisture data
    $stmt = $conn->prepare("INSERT INTO moisture_data (moisture_level, moisture_timestamp) VALUES (?, ?)");
    $stmt->bind_param("is", $moisture, $timestamp);

    if ($stmt->execute()) {
        echo "✅ Data inserted at $timestamp";
    } else {
        error_log("Database error: " . $stmt->error);
        echo "❌ Error: Failed to insert data";
    }
    $stmt->close();
} else {
    echo "⚠️ No data received";
}

$conn->close();
?>