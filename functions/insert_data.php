<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esp-data"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['moisture'])) {
    $moisture = intval($_GET['moisture']); // Convert to integer for safety

    // 💾 Insert into soil_data with automatic timestamp
    $sql = "INSERT INTO soil_data (moisture_level, created_at) VALUES ('$moisture', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Data inserted successfully: $moisture%";
    } else {
        echo "❌ Error inserting data: " . $conn->error;
    }
} else {
    echo "⚠️ No moisture data received";
}

$conn->close();
?>
