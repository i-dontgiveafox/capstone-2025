<?php
$servername = "srv2054.hstgr.io";
$username = "vermicast2025";
$password = "Admin@vermicast2025";
$dbname = "u950148460_espdata";

$conn = new mysqli($servername, $username, $password, $dbname);

$result = mysqli_query($conn, "SELECT relay_name, mode, state FROM relay_control");
while($row = mysqli_fetch_assoc($result)) {
  echo $row['relay_name'] . ":" . $row['mode'] . ":" . $row['state'] . "\n";
}
?>
