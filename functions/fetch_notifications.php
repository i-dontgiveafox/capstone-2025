<?php
// ../functions/fetch_notifications.php
header('Content-Type: application/json');

// ✅ FIX: Set Timezone to Philippines (Manila)
date_default_timezone_set('Asia/Manila');

// =========================================================
// 🚀 CACHING SYSTEM (Prevents Database Overload)
// =========================================================
$cacheFile = 'notifications_cache.json';
$cacheTime = 60; // 60 Seconds

// 1. Check if cache exists and is fresh
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    readfile($cacheFile);
    exit; 
}

// =========================================================
// 🔄 DATABASE LOGIC
// =========================================================

require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// If DB fails, try to return old cache
if ($conn->connect_error) { 
    if (file_exists($cacheFile)) { readfile($cacheFile); exit; }
    die(json_encode([])); 
}

$alerts = [];

// Get Threshold
$gasThreshold = 6.0; 
$sqlThreshold = "SELECT value FROM co2_threshold WHERE id = 1 LIMIT 1";
$resultThreshold = $conn->query($sqlThreshold);
if ($resultThreshold && $resultThreshold->num_rows > 0) {
    $row = $resultThreshold->fetch_assoc();
    $gasThreshold = floatval($row['value']);
}

// 1. FETCH GAS ALERTS
$sqlGas = "SELECT * FROM gas_data 
           WHERE gas_percent >= $gasThreshold 
           ORDER BY gas_id DESC LIMIT 10"; 

$resultGas = $conn->query($sqlGas);

if ($resultGas) {
    while($row = $resultGas->fetch_assoc()) {
        $alerts[] = [
            'type' => 'gas',
            'message' => 'High Gas Detected! (' . $row['gas_percent'] . '%)',
            // ✅ Time is now formatted to Manila Time automatically
            'time' => date("g:i A", strtotime($row['gas_timestamp'])),
            'raw_time' => strtotime($row['gas_timestamp']),
            'is_read' => $row['is_read'] 
        ];
    }
}

// 2. FETCH WATER ALERTS
$sqlWater = "SELECT * FROM water_level 
             WHERE status = 'LOW' 
             ORDER BY id DESC LIMIT 10";

$resultWater = $conn->query($sqlWater);

if ($resultWater) {
    while($row = $resultWater->fetch_assoc()) {
        $alerts[] = [
            'type' => 'water',
            'message' => 'Water Level is Low!',
            // ✅ Time is now formatted to Manila Time automatically
            'time' => date("g:i A", strtotime($row['timestamp'])),
            'raw_time' => strtotime($row['timestamp']),
            'is_read' => $row['is_read'] 
        ];
    }
}

// Sort
usort($alerts, function($a, $b) {
    return $b['raw_time'] - $a['raw_time'];
});

$alerts = array_slice($alerts, 0, 10);

// =========================================================
// 💾 SAVE CACHE
// =========================================================
$jsonOutput = json_encode($alerts);
file_put_contents($cacheFile, $jsonOutput);
echo $jsonOutput;

$conn->close();
?>