<?php
$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT threshold_value FROM soil_threshold WHERE id = 1");
if ($row = $result->fetch_assoc()) {
    echo $row['threshold_value'];
} else {
    echo "55"; // Default value if not found
}
$conn->close();
?>
