<?php
// functions/save_log.php
require_once __DIR__ . '/../config/db_conn.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if data was sent via POST
if (isset($_POST['type']) && isset($_POST['message'])) {
    
    $type = $conn->real_escape_string($_POST['type']);     // e.g., "Fan"
    $desc = $conn->real_escape_string($_POST['message']);  // e.g., "Fan turned OFF"

    // Insert into database
    // Note: 'NOW()' uses the database server's current time
    $sql = "INSERT INTO system_logs (event_type, description, created_at) VALUES ('$type', '$desc', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        echo "Log Saved";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Missing data";
}

$conn->close();
?>