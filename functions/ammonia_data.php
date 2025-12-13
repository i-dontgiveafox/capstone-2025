<?php
// functions/ammonia_data.php

require_once __DIR__ . '/../config/db_conn.php';

// --- NEW CODE: Include the email helper ---
require_once 'send_email_alert.php'; 

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

            // ============================================================
            // 🚀 EMAIL ALERT LOGIC
            // ============================================================
            
            // 1. Get the Threshold
            // (We try to fetch it from the DB, otherwise default to 6.0)
            $threshold = 6.0; 
            $threshSql = "SELECT value FROM co2_threshold WHERE id = 1 LIMIT 1";
            $threshResult = $conn->query($threshSql);
            
            if ($threshResult && $threshResult->num_rows > 0) {
                $row = $threshResult->fetch_assoc();
                $threshold = floatval($row['value']);
            }

            // 2. Check and Send Alert
            if ($ammonia_value >= $threshold) {
                // This function handles the 30-minute cooldown automatically
                sendEmailAlert('ammonia', $ammonia_value);
            }
            // ============================================================

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