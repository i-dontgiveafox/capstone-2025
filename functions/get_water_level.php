<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT water_value, status, timestamp FROM water_level ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "water_value" => $row['water_value'],
        "status" => $row['status'],
        "last_update" => $row['timestamp']
    ]);
} else {
    echo json_encode([
        "water_value" => null,
        "status" => "--",
        "last_update" => "--"
    ]);
}
?>
