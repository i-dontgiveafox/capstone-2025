<?php
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$default_value = '31'; // Default Temperature threshold is 31%

try {
    // Select the 'value' from the temp_threshold table
    $sql = "SELECT value FROM temp_threshold WHERE id = 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo htmlspecialchars($row['value']);
    } else {
        // If the row doesn't exist, return the default value
        echo $default_value;
    }
    
    $conn->close();

} catch (Exception $e) {
    error_log("Database error in get_temp_threshold.php: " . $e->getMessage());
    echo $default_value;
}
?>