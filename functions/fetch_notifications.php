<?php
// functions/fetch_notifications.php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/../config/db_conn.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die(json_encode([])); }

// Settings
$gasThreshold = 0.05; 
$candidates = [];

// --- HELPER FUNCTION: Convert UTC Database Time to Manila Time ---
function parseToManila($dateString) {
    try {
        // 1. Tell PHP the string is in UTC
        $dt = new DateTime($dateString, new DateTimeZone('UTC'));
        // 2. Convert it to Manila Time
        $dt->setTimezone(new DateTimeZone('Asia/Manila'));
        // 3. Return the timestamp
        return $dt->getTimestamp();
    } catch (Exception $e) {
        return time(); // Fallback to now if error
    }
}

// =========================================================
// 1. FETCH WATER ALERTS
// =========================================================
$sqlWater = "SELECT * FROM water_level WHERE status = 'LOW' ORDER BY id DESC LIMIT 20";
$resultWater = $conn->query($sqlWater);
if ($resultWater) {
    while($row = $resultWater->fetch_assoc()) {
        $candidates[] = [
            'category' => 'Water',
            'type' => 'water',
            'message' => 'Water Level is Critical!',
            // USE HELPER FUNCTION HERE
            'raw_time' => parseToManila($row['timestamp']), 
            'is_read' => $row['is_read'] ?? 0
        ];
    }
}

// =========================================================
// 2. FETCH AMMONIA ALERTS (Raw Data)
// =========================================================
$sqlGas = "SELECT * FROM gas_data WHERE gas_percent >= $gasThreshold ORDER BY gas_id DESC LIMIT 20";
$resultGas = $conn->query($sqlGas);
if ($resultGas) {
    while($row = $resultGas->fetch_assoc()) {
        $candidates[] = [
            'category' => 'Ammonia', 
            'type' => 'gas',
            'message' => 'High Ammonia Detected! (' . $row['gas_percent'] . '%)',
            // USE HELPER FUNCTION HERE
            'raw_time' => parseToManila($row['gas_timestamp']),
            'is_read' => $row['is_read'] ?? 0
        ];
    }
}

// =========================================================
// 3. FETCH ALERTS FROM ESP32 (Notifications Table)
// =========================================================
$sqlNotif = "SELECT * FROM notifications ORDER BY time DESC LIMIT 20";
$resultNotif = $conn->query($sqlNotif);
if ($resultNotif) {
    while($row = $resultNotif->fetch_assoc()) {
        $msg = $row['message'];

        if (stripos($msg, 'Water') !== false) {
            continue; 
        }

        $cat = 'Temp'; 
        if (stripos($msg, 'Ammonia') !== false) {
            $cat = 'Ammonia';
        }

        $candidates[] = [
            'category' => $cat,   
            'type' => 'gas',      
            'message' => $msg,
            // USE HELPER FUNCTION HERE
            'raw_time' => parseToManila($row['time']),
            'is_read' => $row['is_read']
        ];
    }
}

// =========================================================
// 4. SORT AND FORMAT
// =========================================================
usort($candidates, function($a, $b) {
    return $b['raw_time'] - $a['raw_time'];
});

$finalAlerts = [];
$spamCooldown = 900; // 15 Minutes
$lastTime = []; // Track last time per CATEGORY

foreach ($candidates as $alert) {
    $cat = $alert['category']; 
    
    // Check spam filter
    if (isset($lastTime[$cat]) && abs($lastTime[$cat] - $alert['raw_time']) < $spamCooldown) {
        continue;
    }
    $lastTime[$cat] = $alert['raw_time'];

    // Format for display
    $alert['time'] = date("M d • g:i A", $alert['raw_time']);
    
    unset($alert['category']);
    
    $finalAlerts[] = $alert;
    if (count($finalAlerts) >= 10) break;
}

echo json_encode($finalAlerts);
$conn->close();
?>