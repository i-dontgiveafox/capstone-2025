<?php
// functions/save_notification.php
require_once __DIR__ . '/../config/db_conn.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed"); }

if (isset($_POST['type']) && isset($_POST['message'])) {
    $type = $conn->real_escape_string($_POST['type']);     // "gas" (Red) or "water" (Blue)
    $msg  = $conn->real_escape_string($_POST['message']);  // e.g. "Critical Temp!"

    // Save to 'notifications' table. 
    // We set 'is_read' to 0 (False) so the badge lights up.
    $sql = "INSERT INTO notifications (type, message, time, is_read) VALUES ('$type', '$msg', NOW(), 0)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Notification Saved";
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>