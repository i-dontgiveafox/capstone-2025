<?php

include 'db_config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, sensor, location, value1, value2, value3, reading_time FROM SensorData ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>ESP32 Sensor Readings</title>
</head>
<body>
    <h1>ESP32 Sensor Readings</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Sensor</th>
            <th>Location</th>
            <th>Value1</th>
            <th>Value2</th>
            <th>Value3</th>
            <th>Timestamp</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row["id"].
                "</td><td>".$row["sensor"].
                "</td><td>".$row["location"].
                "</td><td>".$row["value1"].
                "</td><td>".$row["value2"].
                "</td><td>".$row["value3"].
                "</td><td>".$row["reading_time"]."
                </td></tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No data found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>