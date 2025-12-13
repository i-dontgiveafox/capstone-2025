<?php
// ../functions/fetch_notifications.php
header('Content-Type: application/json');

// This sets the default timezone for new dates, but we still need to convert DB dates manually
date_default_timezone_set('Asia/Manila');

// =========================================================
// 🚀 CACHING SYSTEM (Prevents Database Overload)
// =========================================================
$cacheFile = 'notifications_cache.json';
$cacheTime = 10; // 10 Seconds

// 1. Check if cache exists and is fresh
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    readfile($cacheFile);
    exit; 
}

require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// If DB fails, try to return old cache
if ($conn->connect_error) { 
    if (file_exists($cacheFile)) { readfile($cacheFile); exit; }
    die(json_encode([])); 
}

$alerts = [];

// Get Threshold
$gasThreshold = 0.05; 
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
        
        // --- FIX START: Convert UTC to Manila Time ---
        $dt = new DateTime($row['gas_timestamp'], new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone('Asia/Manila'));
        $formattedTime = $dt->format("g:i A");
        // --- FIX END ---

        $alerts[] = [
            'type' => 'gas',
            'message' => 'High Gas Detected! (' . $row['gas_percent'] . '%)',
            'time' => $formattedTime, // Uses the converted time
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

        // --- FIX START: Convert UTC to Manila Time ---
        $dt = new DateTime($row['timestamp'], new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone('Asia/Manila'));
        $formattedTime = $dt->format("g:i A");
        // --- FIX END ---

        $alerts[] = [
            'type' => 'water',
            'message' => 'Water Level is Low!',
            'time' => $formattedTime, // Uses the converted time
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