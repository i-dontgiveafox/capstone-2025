<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch the latest ammonia data
$sql = "SELECT ammonia_value, timestamp FROM ammonia_readings ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'ammonia_value' => $row['ammonia_value'],
        'ammonia_last'  => $row['timestamp']
    ]);
} else {
    echo json_encode([
        'ammonia_value' => null,
        'ammonia_last'  => null
    ]);
}

$conn->close();
?>
