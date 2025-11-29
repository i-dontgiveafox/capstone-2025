<?php
// simulate_data.php
require_once __DIR__ . '/../config/db.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// ➤ INSERT GAS DATA (Columns: gas_percent, gas_status, gas_timestamp)
if (isset($_GET['gas'])) {
    $gasValue = floatval($_GET['gas']);
    $statusStr = ($gasValue > 6.0) ? "HIGH" : "NORMAL";

    $sql = "INSERT INTO gas_data (gas_percent, gas_status, gas_timestamp) 
            VALUES ('$gasValue', '$statusStr', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        echo "✅ Success! Inserted Gas: $gasValue% ($statusStr)<br>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// ➤ INSERT WATER DATA (Columns: water_value, status, timestamp)
if (isset($_GET['water'])) {
    $waterStatus = $_GET['water']; // 'LOW' or 'HIGH'
    $val = ($waterStatus == 'LOW') ? 0 : 100; // 0 for LOW, 100 for HIGH
    
    // ✅ FIXED: Using 'timestamp' instead of 'created_at'
    $sql = "INSERT INTO water_level (water_value, status, timestamp) 
            VALUES ('$val', '$waterStatus', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        echo "✅ Success! Inserted Water: $waterStatus<br>";
    } else {
        echo "Error: " . $conn->error;
    }
}

echo "<hr><h3>Test Links:</h3><ul>";
echo "<li><a href='?gas=100'>🔴 Trigger HIGH GAS (100%)</a></li>";
echo "<li><a href='?gas=0'>🟢 Reset GAS (0%)</a></li>";
echo "<li><a href='?water=LOW'>🔵 Trigger LOW WATER</a></li>";
echo "<li><a href='?water=HIGH'>⚪ Reset WATER</a></li>";
echo "</ul>";

$conn->close();
?>