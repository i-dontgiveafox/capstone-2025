<?php
$conn = new mysqli("localhost", "root", "", "esp-data");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id, moisture_level FROM soil_data ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Soil Moisture Data</title>
  <style>
    body { font-family: Arial; margin: 20px; }
    table { border-collapse: collapse; width: 60%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
  <h2>🌿 Soil Moisture Readings (in %)</h2>
  <table>
    <tr><th>ID</th><th>Moisture Level (%)</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['moisture_level'] ?>%</td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
