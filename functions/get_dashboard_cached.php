<?php
// functions/get_dashboard_cached.php
header('Content-Type: application/json');


ini_set('display_errors', 0);
error_reporting(E_ALL);


$cacheFile = 'dashboard_data.json';
$cacheTime = 20; 

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    readfile($cacheFile);
    exit; 
}


require '../config/db_conn.php'; 

// Ensure connection exists
if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = new mysqli($servername, $username, $password, $dbname);
}

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$response = [];

try {
    // --- A. STATUS (From heartbeat_data) ---
    $sql = "SELECT last_seen FROM heartbeat_data ORDER BY heartbeat_id DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        $last_seen_ts = strtotime($row['last_seen']);
        $is_online = (time() - $last_seen_ts) < 60; 
        $response['status'] = [
            'status' => $is_online ? 'online' : 'offline',
            'last_seen' => $row['last_seen']
        ];
    } else {
        $response['status'] = ['status' => 'offline', 'last_seen' => '--'];
    }

    // --- B. LATEST SENSOR DATA ---
    
    // 1. Temp & Humidity
    $dht = ['temp_heat' => 0, 'temp_humid' => 0, 'temp_timestamp' => date('Y-m-d H:i:s')];
    $res = $conn->query("SELECT temp_heat, temp_humid, temp_timestamp FROM dht11_data ORDER BY temp_id DESC LIMIT 1");
    if ($res && $r = $res->fetch_assoc()) $dht = $r;

    // 2. Moisture
    $moist = ['moisture_level' => 0];
    $res = $conn->query("SELECT moisture_level FROM moisture_data ORDER BY moisture_id DESC LIMIT 1");
    if ($res && $r = $res->fetch_assoc()) $moist = $r;

    // 3. Gas
    $gas = ['gas_percent' => 0];
    $res = $conn->query("SELECT gas_percent FROM gas_data ORDER BY gas_id DESC LIMIT 1");
    if ($res && $r = $res->fetch_assoc()) $gas = $r;

    // 4. Ammonia
    $amm = ['ammonia_value' => 0];
    $res = $conn->query("SELECT ammonia_value FROM ammonia_readings ORDER BY id DESC LIMIT 1");
    if ($res && $r = $res->fetch_assoc()) $amm = $r;

    $response['sensors'] = [
        'temperature' => $dht['temp_heat'],
        'humidity'    => $dht['temp_humid'],
        'moisture'    => $moist['moisture_level'],
        'methane'     => $gas['gas_percent'],
        'ammonia'     => $amm['ammonia_value'],
        'timestamp'   => $dht['temp_timestamp']
    ];

    // --- C. WATER LEVEL ---
    $wat = ['water_value' => '--', 'timestamp' => '--'];
    $res = $conn->query("SELECT water_value, timestamp FROM water_level ORDER BY id DESC LIMIT 1");
    if ($res && $r = $res->fetch_assoc()) $wat = $r;
    
    $response['water'] = [
        'water_value' => $wat['water_value'], 
        'last_update' => $wat['timestamp']
    ];

    // --- D. DAILY AVERAGES ---
    // NOTE: I removed the "WHERE DATE = CURDATE()" filter temporarily so you can see data 
    // from previous days. Add it back when you are live!
    
    $avg_dht = $conn->query("SELECT AVG(temp_heat) as avg_temp, AVG(temp_humid) as avg_humid FROM dht11_data")->fetch_assoc();
    $avg_moist = $conn->query("SELECT AVG(moisture_level) as avg_moisture FROM moisture_data")->fetch_assoc();
    $avg_gas = $conn->query("SELECT AVG(gas_percent) as avg_gas FROM gas_data")->fetch_assoc();
    $avg_amm = $conn->query("SELECT AVG(ammonia_value) as avg_ammonia FROM ammonia_readings")->fetch_assoc();

    $response['averages'] = [
        'avg_temp'     => $avg_dht['avg_temp'] ?? 0,
        'avg_humid'    => $avg_dht['avg_humid'] ?? 0,
        'avg_moisture' => $avg_moist['avg_moisture'] ?? 0,
        'avg_gas'      => $avg_gas['avg_gas'] ?? 0,
        'avg_ammonia'  => $avg_amm['avg_ammonia'] ?? 0
    ];

    // 4. SAVE TO FILE
    file_put_contents($cacheFile, json_encode($response));

    // 5. OUTPUT DATA
    echo json_encode($response);

} catch(Exception $e) {
    if (file_exists($cacheFile)) {
        readfile($cacheFile);
    } else {
        echo json_encode(["error" => "Script Error: " . $e->getMessage()]);
    }
}

$conn->close();
?>