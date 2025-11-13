<?php
$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";

$conn = new mysqli($servername, $username, $password, $dbname);

$result = mysqli_query($conn, "SELECT duration_ms FROM sprinkler_settings WHERE id = 1");
if ($row = mysqli_fetch_assoc($result)) {
    echo $row['duration_ms'];
} else {
    echo "2000"; // default
}
?>
