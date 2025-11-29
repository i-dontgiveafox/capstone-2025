<?php
require_once __DIR__ . '/../config/db.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ⚠️ FIXED: Changed default from '6' to '0.05' to match your new settings
$default_value = '0.05'; 

try {
    // Select the 'value' from the co2_threshold table
    $sql = "SELECT value FROM co2_threshold WHERE id = 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo htmlspecialchars($row['value']);
    } else {
        // If the row doesn't exist, return the default value (0.05)
        echo $default_value;
    }
    
    $conn->close();

} catch (Exception $e) {
    error_log("Database error in get_co2_threshold.php: " . $e->getMessage());
    echo $default_value;
}
?>