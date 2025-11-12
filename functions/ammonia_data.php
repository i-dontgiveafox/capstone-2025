<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read POSTed field
    if (isset($_POST['ammonia_value'])) {
        $ammonia_value = floatval($_POST['ammonia_value']);

        // Prepare and execute insert
        $stmt = $conn->prepare("INSERT INTO ammonia_readings (ammonia_value, timestamp) VALUES (?, NOW())");
        if (!$stmt) {
            die("❌ Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("d", $ammonia_value);

        if ($stmt->execute()) {
            echo "✅ Data saved successfully";
        } else {
            echo "❌ Execute failed: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "⚠️ Missing POST field: ammonia_value";
    }
} else {
    echo "⚠️ Use POST method only.";
}

$conn->close();
?>
