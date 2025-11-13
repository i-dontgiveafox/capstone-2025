<?php
header('Content-Type: application/json');

// Database connection
$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// --- Soil Moisture Average (latest 20 readings) ---
$moisture_sql = "
    SELECT AVG(CAST(moisture_level AS DECIMAL(5,2))) AS avg_moisture 
    FROM (
        SELECT moisture_level 
        FROM moisture_data 
        ORDER BY moisture_id DESC 
        LIMIT 20
    ) AS recent
";
$moisture_result = $conn->query($moisture_sql);
$avg_moisture = $moisture_result->fetch_assoc()['avg_moisture'] ?? 0;

// --- Temperature Average (latest 20 readings) ---
$temp_sql = "
    SELECT AVG(CAST(temp_heat AS DECIMAL(5,2))) AS avg_temp
    FROM (
        SELECT temp_heat 
        FROM dht11_data 
        ORDER BY temp_id DESC 
        LIMIT 20
    ) AS recent
";
$temp_result = $conn->query($temp_sql);
$avg_temp = $temp_result->fetch_assoc()['avg_temp'] ?? 0;

// --- Humidity Average (latest 20 readings) ---
$humid_sql = "
    SELECT AVG(CAST(temp_humid AS DECIMAL(5,2))) AS avg_humid
    FROM (
        SELECT temp_humid 
        FROM dht11_data 
        ORDER BY temp_id DESC 
        LIMIT 20
    ) AS recent
";
$humid_result = $conn->query($humid_sql);
$avg_humid = $humid_result->fetch_assoc()['avg_humid'] ?? 0;

// --- CO2 Gas Average (latest 20 readings) ---
$co2_sql = "
    SELECT AVG(CAST(gas_percent AS DECIMAL(5,2))) AS avg_co2
    FROM (
        SELECT gas_percent 
        FROM gas_data 
        ORDER BY gas_id DESC 
        LIMIT 20
    ) AS recent
";
$co2_result = $conn->query($co2_sql);
$avg_co2 = $co2_result->fetch_assoc()['avg_co2'] ?? 0;

// --- Ammonia Average (latest 20 readings) ---
$ammonia_sql = "
    SELECT AVG(CAST(ammonia_value AS DECIMAL(5,2))) AS avg_ammonia
    FROM (
        SELECT ammonia_value 
        FROM ammonia_readings 
        ORDER BY id DESC 
        LIMIT 20
    ) AS recent
";
$ammonia_result = $conn->query($ammonia_sql);
$avg_ammonia = $ammonia_result->fetch_assoc()['avg_ammonia'] ?? 0;

// --- Return all results as JSON ---
echo json_encode([
    "avg_moisture" => round($avg_moisture, 2),
    "avg_temp" => round($avg_temp, 2),
    "avg_humid" => round($avg_humid, 2),
    "avg_co2" => round($avg_co2, 2),
    "avg_ammonia" => round($avg_ammonia, 2)
]);

$conn->close();
?>
