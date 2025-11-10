<?php
$servername = "localhost";
$username = "root";  // adjust if needed
$password = "";
$dbname = "esp-data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['gas'])) {
  $gas = $_GET['gas'];
  $status = ($gas > 300) ? "Alert" : "Normal"; // threshold 300 (adjust as needed)

  $sql = "INSERT INTO gas_readings (gas_level, status) VALUES ('$gas', '$status')";
  if ($conn->query($sql) === TRUE) {
    echo "Data saved successfully";
  } else {
    echo "Error: " . $conn->error;
  }
} else {
  echo "No data received";
}

$conn->close();
?>
