<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Get today's data grouped hourly
$sql = "
    SELECT 
        HOUR(temp_timestamp) AS hour,
        ROUND(AVG(temp_heat), 2) AS avg_temp,
        ROUND(AVG(temp_humid), 2) AS avg_humid
    FROM dht11_data
    WHERE DATE(temp_timestamp) = CURDATE()
    GROUP BY HOUR(temp_timestamp)
    ORDER BY hour ASC
";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "hour" => sprintf("%02d:00", $row['hour']),
            "avg_temp" => $row['avg_temp'],
            "avg_humid" => $row['avg_humid']
        ];
    }
}

echo json_encode($data);
$conn->close();
?>
