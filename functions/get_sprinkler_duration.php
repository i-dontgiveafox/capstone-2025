<?php
require_once __DIR__ . '/../config/db.php';


$conn = new mysqli($servername, $username, $password, $dbname);

$result = mysqli_query($conn, "SELECT duration_ms FROM sprinkler_settings WHERE id = 1");
if ($row = mysqli_fetch_assoc($result)) {
    echo $row['duration_ms'];
} else {
    echo "2000"; // default
}
?>
