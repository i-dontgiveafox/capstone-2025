<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

if (isset($_GET['moisture'])) {
    $moisture = intval($_GET['moisture']);

    date_default_timezone_set('Asia/Manila');
    $timestamp = date('Y-m-d H:i:s');

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
