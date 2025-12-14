<?php
// functions/get_dashboard_cached.php
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

// Force UTC
date_default_timezone_set('UTC'); 

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

// Helper: Safe Fetch
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

// --- B. SENSORS (Latest Reading) ---
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

// --- D. AVERAGES (STANDARD DAILY: 00:00 to NOW) ---
$today = date('Y-m-d');

// 1. Calculate TODAY'S Average (Whole Day)
try {
    $dht_today = $conn->query("SELECT AVG(temp_heat) as t, AVG(temp_humid) as h FROM dht11_data WHERE DATE(temp_timestamp) = '$today'")->fetch_assoc();
    $moist_today = $conn->query("SELECT AVG(moisture_level) as m FROM moisture_data WHERE DATE(created_at) = '$today'")->fetch_assoc();
    $gas_today = $conn->query("SELECT AVG(gas_percent) as g FROM gas_data WHERE DATE(gas_timestamp) = '$today'")->fetch_assoc();
    $amm_today = $conn->query("SELECT AVG(ammonia_value) as a FROM ammonia_readings WHERE DATE(created_at) = '$today'")->fetch_assoc();
} catch (Exception $e) { }

// 2. Calculate LAST 20 READINGS Average
try {
    $dht_20 = $conn->query("SELECT AVG(temp_heat) as t, AVG(temp_humid) as h FROM (SELECT temp_heat, temp_humid FROM dht11_data ORDER BY temp_id DESC LIMIT 20) as sub")->fetch_assoc();
    $moist_20 = $conn->query("SELECT AVG(moisture_level) as m FROM (SELECT moisture_level FROM moisture_data ORDER BY moisture_id DESC LIMIT 20) as sub")->fetch_assoc();
    $gas_20 = $conn->query("SELECT AVG(gas_percent) as g FROM (SELECT gas_percent FROM gas_data ORDER BY gas_id DESC LIMIT 20) as sub")->fetch_assoc();
    $amm_20 = $conn->query("SELECT AVG(ammonia_value) as a FROM (SELECT ammonia_value FROM ammonia_readings ORDER BY id DESC LIMIT 20) as sub")->fetch_assoc();
} catch (Exception $e) { }

// Compile Response
$response['averages'] = [
    'today' => [
        'avg_temp'     => $dht_today['t'] ?? 0,
        'avg_humid'    => $dht_today['h'] ?? 0,
        'avg_moisture' => $moist_today['m'] ?? 0,
        'avg_gas'      => $gas_today['g'] ?? 0,
        'avg_ammonia'  => $amm_today['a'] ?? 0
    ],
    'last20' => [
        'avg_temp'     => $dht_20['t'] ?? 0,
        'avg_humid'    => $dht_20['h'] ?? 0,
        'avg_moisture' => $moist_20['m'] ?? 0,
        'avg_gas'      => $gas_20['g'] ?? 0,
        'avg_ammonia'  => $amm_20['a'] ?? 0
    ]
];

file_put_contents($cacheFile, json_encode($response));
echo json_encode($response);

$conn->close();
?>