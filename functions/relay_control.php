<?php
$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";

$conn = new mysqli($servername, $username, $password, $dbname);

$relay = $_GET['relay'] ?? '';
$state = $_GET['state'] ?? '';
$mode  = $_GET['mode'] ?? 'MANUAL';

if ($relay && $state) {
    $sql = "UPDATE relay_control SET state='$state', mode='$mode' WHERE relay_name='$relay'";
    mysqli_query($conn, $sql);
    
    // Echo in ESP32 expected format
    echo "$relay:$mode:$state";
} else {
    echo "Missing parameters";
}
?>
