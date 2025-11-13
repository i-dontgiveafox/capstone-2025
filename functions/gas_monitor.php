<?php
$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['gas_percent']) && isset($_POST['status'])){
  $gas = $_POST['gas_percent'];
  $status = $_POST['status'];
  
  $sql = "INSERT INTO gas_data (gas_percent, gas_status) VALUES ('$gas', '$status')";
  
  if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
$conn->close();
?>
