<?php
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['moisture'])) {
    $moisture = intval($_GET['moisture']); // Convert to integer for safety

    // 💾 Insert into soil_data with automatic timestamp using prepared statement
    $stmt = $conn->prepare("INSERT INTO soil_data (moisture_level, created_at) VALUES (?, NOW())");
    $stmt->bind_param("i", $moisture);

    if ($stmt->execute()) {
        echo "✅ Data inserted successfully: $moisture%";
    } else {
        error_log("Database error: " . $stmt->error);
        echo "❌ Error inserting data";
    }
    $stmt->close();
} else {
    echo "⚠️ No moisture data received";
}

$conn->close();
?>
