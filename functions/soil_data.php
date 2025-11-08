<?php
$servername = "localhost";
$username = "root";
$password = ""; // default XAMPP password is empty
$dbname = "esp-data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

if (isset($_GET['moisture'])) {
    $moisture = intval($_GET['moisture']);

    // Set timezone and create timestamp
    date_default_timezone_set('Asia/Manila');
    $timestamp = date('Y-m-d H:i:s');

    // Insert data with timestamp
    $sql = "INSERT INTO soil_data (moisture_level, timestamp) VALUES ($moisture, '$timestamp')";

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
