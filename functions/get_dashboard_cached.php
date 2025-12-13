<?php
// functions/get_dashboard_cached.php
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

// --- FIX 1: FORCE UTC TIMEZONE ---
// This ensures time() matches the UTC timestamp in your database
date_default_timezone_set('UTC'); 
// ---------------------------------

ini_set('display_errors', 0);
error_reporting(E_ALL);

$cacheFile = 'dashboard_data.json';
$cacheTime = 5; 

// Serve cache if fresh
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    readfile($cacheFile);
    exit; 
}

require '../config/db_conn.php'; 

if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = new mysqli($servername, $username, $password, $dbname);
}

// Helper: Safe Fetch (Returns default if table is missing/empty)
function safeFetch($conn, $query, $default) {
    try {
        $res = $conn->query($query);
        if ($res && $r = $res->fetch_assoc()) {
            return $r;
        }
    } catch (Exception $e) {
        return $default;
    }
    return $default;
}

$response = [];

// --- A. STATUS ---
try {
    $result = $conn->query("SELECT last_seen FROM heartbeat_data ORDER BY heartbeat_id DESC LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        $last_seen_ts = strtotime($row['last_seen']);
        
        // --- FIX 2: INCREASE TIMEOUT TO 60 SECONDS ---
        // (time() - last_seen) < 60 is safer than 25
        $is_online = (time() - $last_seen_ts) < 60; 
        
        $response['status'] = [
            'status' => $is_online ? 'online' : 'offline',
            'last_seen' => $row['last_seen']
        ];
    } else {
        throw new Exception("No Data");
    }
} catch (Exception $e) {
    $response['status'] = ['status' => 'offline', 'last_seen' => '--'];
}

// --- B. SENSORS ---
$dht = safeFetch($conn, 
    "SELECT temp_heat, temp_humid, temp_timestamp FROM dht11_data ORDER BY temp_id DESC LIMIT 1", 
    ['temp_heat' => 0, 'temp_humid' => 0, 'temp_timestamp' => date('Y-m-d H:i:s')]
);

$moist = safeFetch($conn, 
    "SELECT moisture_level FROM moisture_data ORDER BY moisture_id DESC LIMIT 1", 
    ['moisture_level' => 0]
);

$gas = safeFetch($conn, 
    "SELECT gas_percent FROM gas_data ORDER BY gas_id DESC LIMIT 1", 
    ['gas_percent' => 0]
);

$amm = safeFetch($conn, 
    "SELECT ammonia_value FROM ammonia_readings ORDER BY id DESC LIMIT 1", 
    ['ammonia_value' => 0]
);

$response['sensors'] = [
    'temperature' => $dht['temp_heat'],
    'humidity'    => $dht['temp_humid'],
    'moisture'    => $moist['moisture_level'],
    'methane'     => $gas['gas_percent'],
    'ammonia'     => $amm['ammonia_value'],
    'timestamp'   => $dht['temp_timestamp']
];

// --- C. WATER LEVEL ---
$wat = safeFetch($conn, 
    "SELECT water_value, timestamp FROM water_level ORDER BY id DESC LIMIT 1", 
    ['water_value' => '--', 'timestamp' => '--']
);

$response['water'] = [
    'water_value' => $wat['water_value'], 
    'last_update' => $wat['timestamp']
];

// --- D. AVERAGES (RESTORED: Filter by Today) ---
$today = date('Y-m-d');

try {
    $avg_dht = $conn->query("SELECT AVG(temp_heat) as avg_temp, AVG(temp_humid) as avg_humid FROM dht11_data WHERE DATE(temp_timestamp) = '$today'")->fetch_assoc();
} catch (Exception $e) { $avg_dht = []; }

try {
    $avg_moist = $conn->query("SELECT AVG(moisture_level) as avg_moisture FROM moisture_data WHERE DATE(created_at) = '$today'")->fetch_assoc();
} catch (Exception $e) { $avg_moist = []; }

try {
    $avg_gas = $conn->query("SELECT AVG(gas_percent) as avg_gas FROM gas_data WHERE DATE(gas_timestamp) = '$today'")->fetch_assoc();
} catch (Exception $e) { $avg_gas = []; }

try {
    $avg_amm = $conn->query("SELECT AVG(ammonia_value) as avg_ammonia FROM ammonia_readings WHERE DATE(created_at) = '$today'")->fetch_assoc();
} catch (Exception $e) { $avg_amm = []; }


$response['averages'] = [
    'avg_temp'     => $avg_dht['avg_temp'] ?? 0,
    'avg_humid'    => $avg_dht['avg_humid'] ?? 0,
    'avg_moisture' => $avg_moist['avg_moisture'] ?? 0,
    'avg_gas'      => $avg_gas['avg_gas'] ?? 0,
    'avg_ammonia'  => $avg_amm['avg_ammonia'] ?? 0
];

file_put_contents($cacheFile, json_encode($response));
echo json_encode($response);

$conn->close();
?>