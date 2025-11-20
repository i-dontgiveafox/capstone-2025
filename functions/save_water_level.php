<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Allow both POST + GET for debugging
$waterValue = $_POST['water_value'] ?? $_GET['water_value'] ?? null;
$status     = $_POST['status'] ?? $_GET['status'] ?? null;

// Debug only (remove after testing)
if ($waterValue === null || $status === null) {
    echo json_encode([
        "success" => false,
        "message" => "Missing water_value or status",
        "received_post" => $_POST,
        "received_get" => $_GET
    ]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO water_level (water_value, status) VALUES (?, ?)");
$stmt->bind_param("is", $waterValue, $status);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Water level saved",
        "water_value" => $waterValue,
        "status" => $status
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Database insert failed",
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
