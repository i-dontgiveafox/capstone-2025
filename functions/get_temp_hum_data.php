<?php

header('Content-Type: application/json');



require_once __DIR__ . '/../config/db.php';





$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {

  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));

}



$sql = "SELECT temp_heat, temp_humid, temp_timestamp

        FROM dht11_data

        ORDER BY temp_id DESC

        LIMIT 20";



$result = $conn->query($sql);

$data = [];



if ($result->num_rows > 0) {

  while ($row = $result->fetch_assoc()) {

    $data[] = [

      "timestamp" => $row["temp_timestamp"],

      "temperature" => (float)$row["temp_heat"],

      "humidity" => (float)$row["temp_humid"]

    ];

  }

}



// Return data oldest → newest for smooth chart

echo json_encode(array_reverse($data));

$conn->close();

?>