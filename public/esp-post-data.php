<?php
$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from ESP via HTTP POST
$sensor = $_POST['sensor'];
$location = $_POST['location'];
$value1 = $_POST['value1'];
$value2 = $_POST['value2'];
$value3 = $_POST['value3'];

// Insert into database
$sql = "INSERT INTO SensorData (sensor, location, value1, value2, value3)
        VALUES ('$sensor', '$location', '$value1', '$value2', '$value3')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>