<?php
// simulate_data.php
require_once __DIR__ . '/../config/db.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// ➤ INSERT GAS DATA (Columns: gas_percent, gas_status, gas_timestamp)
if (isset($_GET['gas'])) {
    $gasValue = floatval($_GET['gas']);
    $statusStr = ($gasValue > 6.0) ? "HIGH" : "NORMAL";

    $stmt = $conn->prepare("INSERT INTO gas_data (gas_percent, gas_status, gas_timestamp) 
            VALUES (?, ?, NOW())");
    $stmt->bind_param("ds", $gasValue, $statusStr);
    
    if ($stmt->execute()) {
        echo "✅ Success! Inserted Gas: $gasValue% ($statusStr)<br>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// ➤ INSERT WATER DATA (Columns: water_value, status, timestamp)
if (isset($_GET['water'])) {
    $waterStatus = $_GET['water']; // 'LOW' or 'HIGH'
    $val = ($waterStatus == 'LOW') ? 0 : 100; // 0 for LOW, 100 for HIGH
    
    $stmt = $conn->prepare("INSERT INTO water_level (water_value, status, timestamp) 
            VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $val, $waterStatus);
    
    if ($stmt->execute()) {
        echo "✅ Success! Inserted Water: $waterStatus<br>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

echo "<hr><h3>Test Links:</h3><ul>";
echo "<li><a href='?gas=100'>🔴 Trigger HIGH GAS (100%)</a></li>";
echo "<li><a href='?gas=0'>🟢 Reset GAS (0%)</a></li>";
echo "<li><a href='?water=LOW'>🔵 Trigger LOW WATER</a></li>";
echo "<li><a href='?water=HIGH'>⚪ Reset WATER</a></li>";
echo "</ul>";

$conn->close();
?>