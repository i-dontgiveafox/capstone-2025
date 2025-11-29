<?php
// ../functions/fetch_notifications.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die(json_encode([])); }

$alerts = [];

// Get Threshold
$gasThreshold = 6.0; 
$sqlThreshold = "SELECT value FROM co2_threshold WHERE id = 1 LIMIT 1";
$resultThreshold = $conn->query($sqlThreshold);
if ($resultThreshold && $resultThreshold->num_rows > 0) {
    $row = $resultThreshold->fetch_assoc();
    $gasThreshold = floatval($row['value']);
}

// 1. FETCH GAS ALERTS (Last 10 High Alerts, Read OR Unread)
$sqlGas = "SELECT * FROM gas_data 
           WHERE gas_percent >= $gasThreshold 
           ORDER BY gas_id DESC LIMIT 10"; 

$resultGas = $conn->query($sqlGas);

if ($resultGas) {
    while($row = $resultGas->fetch_assoc()) {
        $alerts[] = [
            'type' => 'gas',
            'message' => 'High Gas Detected! (' . $row['gas_percent'] . '%)',
            'time' => date("g:i A", strtotime($row['gas_timestamp'])),
            'raw_time' => strtotime($row['gas_timestamp']),
            'is_read' => $row['is_read'] // ✅ Sending this to JS
        ];
    }
}

// 2. FETCH WATER ALERTS (Last 10 Low Alerts, Read OR Unread)
$sqlWater = "SELECT * FROM water_level 
             WHERE status = 'LOW' 
             ORDER BY id DESC LIMIT 10";

$resultWater = $conn->query($sqlWater);

if ($resultWater) {
    while($row = $resultWater->fetch_assoc()) {
        $alerts[] = [
            'type' => 'water',
            'message' => 'Water Level is Low!',
            'time' => date("g:i A", strtotime($row['timestamp'])),
            'raw_time' => strtotime($row['timestamp']),
            'is_read' => $row['is_read'] // ✅ Sending this to JS
        ];
    }
}

// Sort by newest
usort($alerts, function($a, $b) {
    return $b['raw_time'] - $a['raw_time'];
});

// Optional: Slice to keep only top 10 total mixed
$alerts = array_slice($alerts, 0, 10);

echo json_encode($alerts);
$conn->close();
?>