<?php
require_once __DIR__ . '/../config/db.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Use $_REQUEST to handle both GET and POST
if (isset($_REQUEST['duration'])) {
    $duration = intval($_REQUEST['duration']);

    if ($duration < 100 || $duration > 10000) {
        echo "error"; 
        exit;
    }

    $sql = "UPDATE sprinkler_settings SET duration_ms = $duration WHERE id = 1";
    if (mysqli_query($conn, $sql)) {
        // THE FIX: Only echo the word "success"
        echo "success"; 
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>