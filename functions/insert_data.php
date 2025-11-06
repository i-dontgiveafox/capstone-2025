<?php
$servername = "localhost";
$username = "root";
$password = ""; // default XAMPP password is empty
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

if (isset($_GET['moisture'])) {
    $moisture = intval($_GET['moisture']);
    $sql = "INSERT INTO soil_data (moisture_level) VALUES ($moisture)";
    if ($conn->query($sql) === TRUE) {
        echo "Data inserted";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No data received";
}

$conn->close();
?>
