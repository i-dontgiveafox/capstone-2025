<?php
// ../functions/fetch_notifications.php
header('Content-Type: application/json');

// Set PHP timezone
date_default_timezone_set('Asia/Manila');

// =========================================================
// ⚙️ SETTINGS
// =========================================================
$cacheFile = 'notifications_cache.json';
$cacheTime = 5; 
$spamCooldown = 900; //15minutes

// 1. Check Cache
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    readfile($cacheFile);
    exit; 
}

require_once __DIR__ . '/../config/db_conn.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) { 
    if (file_exists($cacheFile)) { readfile($cacheFile); exit; }
    die(json_encode([])); 
}

// Array to hold all raw candidates
$candidates = [];

// Get Threshold
$gasThreshold = 0.05; 
/* Optional: Fetch from DB
$sqlThreshold = "SELECT value FROM co2_threshold WHERE id = 1 LIMIT 1";
$resultThreshold = $conn->query($sqlThreshold);
if ($resultThreshold && $resultThreshold->num_rows > 0) {
    $row = $resultThreshold->fetch_assoc();
    $gasThreshold = floatval($row['value']);
}
*/

// =========================================================
// 2. FETCH RAW DATA (Increased Limit to 50 to find history)
// =========================================================

// --- GAS ---
$sqlGas = "SELECT * FROM gas_data 
           WHERE gas_percent >= $gasThreshold 
           ORDER BY gas_id DESC LIMIT 50"; 

$resultGas = $conn->query($sqlGas);
if ($resultGas) {
    while($row = $resultGas->fetch_assoc()) {
        $candidates[] = [
            'type' => 'gas',
            'message' => 'High Gas Detected! (' . $row['gas_percent'] . '%)',
            'timestamp_str' => $row['gas_timestamp'], // Keep raw string for formatting
            'raw_time' => strtotime($row['gas_timestamp']),
            'is_read' => $row['is_read'] ?? 0 
        ];
    }
}

// --- WATER ---
$sqlWater = "SELECT * FROM water_level 
             WHERE status = 'LOW' 
             ORDER BY id DESC LIMIT 50";

$resultWater = $conn->query($sqlWater);
if ($resultWater) {
    while($row = $resultWater->fetch_assoc()) {
        $candidates[] = [
            'type' => 'water',
            'message' => 'Water Level is Low!',
            'timestamp_str' => $row['timestamp'],
            'raw_time' => strtotime($row['timestamp']),
            'is_read' => $row['is_read'] ?? 0 
        ];
    }
}

// =========================================================
// 3. SORT & SPAM FILTER
// =========================================================

// Sort candidates by Time (Newest First)
usort($candidates, function($a, $b) {
    return $b['raw_time'] - $a['raw_time'];
});

$finalAlerts = [];
$lastGasTime = 0;
$lastWaterTime = 0;

foreach ($candidates as $alert) {
    $isSpam = false;

    // Check Cooldown based on Type
    if ($alert['type'] === 'gas') {
        // If this alert is within X minutes of the previously accepted gas alert, skip it
        if ($lastGasTime !== 0 && abs($lastGasTime - $alert['raw_time']) < $spamCooldown) {
            $isSpam = true;
        } else {
            $lastGasTime = $alert['raw_time']; // Update last accepted time
        }
    } 
    elseif ($alert['type'] === 'water') {
        if ($lastWaterTime !== 0 && abs($lastWaterTime - $alert['raw_time']) < $spamCooldown) {
            $isSpam = true;
        } else {
            $lastWaterTime = $alert['raw_time'];
        }
    }

    // Only add if it passed the spam check
    if (!$isSpam) {
        // Format the date nicely now that we know we are keeping it
        try {
            $dt = new DateTime($alert['timestamp_str'], new DateTimeZone('UTC'));
            $dt->setTimezone(new DateTimeZone('Asia/Manila'));
            $formattedTime = $dt->format("M d • g:i A");
        } catch (Exception $e) {
            $formattedTime = $alert['timestamp_str'];
        }

        // Add formatted time to the array
        $alert['time'] = $formattedTime;
        
        // Remove temporary keys to keep JSON clean
        unset($alert['timestamp_str']);

        $finalAlerts[] = $alert;
    }

    // Stop once we have 10 clean notifications
    if (count($finalAlerts) >= 10) break;
}

// =========================================================
// 💾 SAVE CACHE
// =========================================================
$jsonOutput = json_encode($finalAlerts);
file_put_contents($cacheFile, $jsonOutput);
echo $jsonOutput;

$conn->close();
?>