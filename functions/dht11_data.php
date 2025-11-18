<?php
require_once __DIR__ . '/../config/db.php';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sensor = $_POST['sensor'];
$location = $_POST['location'];
$value1 = $_POST['value1'];
$value2 = $_POST['value2'];
$value3 = $_POST['value3'];

$sql = "INSERT INTO dht11_data (temp_sensor, temp_location, temp_heat, temp_humid, value3)
        VALUES ('$sensor', '$location', '$value1', '$value2', '$value3')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>