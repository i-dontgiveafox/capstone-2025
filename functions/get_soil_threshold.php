<?php
require_once __DIR__ . '/../config/db.php';


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
