<?php
$servername = "localhost";
$username = "root"; 
$password = "";    
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_GET['duration'])) {
    $duration = intval($_GET['duration']);

    if ($duration < 100 || $duration > 10000) {
        echo "❌ Invalid duration";
        exit;
    }

    // Update the single record (id = 1)
    $sql = "UPDATE sprinkler_settings SET duration_ms = $duration WHERE id = 1";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Duration updated to {$duration} ms";
    } else {
        echo "❌ Failed to update duration";
    }
} else {
    echo "❌ Duration not provided";
}
?>
