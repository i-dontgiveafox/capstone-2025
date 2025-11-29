<?php
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if (isset($_POST['co2_threshold'])) {
    // ⚠️ CHANGE 1: Validate as FLOAT, not INT
    $new_threshold = filter_var($_POST['co2_threshold'], FILTER_VALIDATE_FLOAT);

    // ⚠️ CHANGE 2: Allow numbers less than 1 (like 0.05). 
    // Changed checks to allow anything between 0 and 100.
    if ($new_threshold === false || $new_threshold < 0 || $new_threshold > 100) {
        http_response_code(400); // Bad Request
        die("Error: Invalid CO2 threshold value provided. Must be a number between 0 and 100.");
    }

    try {
        // Update the value for the single row (ID 1)
        $sql = "UPDATE co2_threshold SET value = ? WHERE id = 1";
        
        $stmt = $conn->prepare($sql);
        
        // ⚠️ CHANGE 3: Changed "i" (integer) to "d" (double/decimal)
        $stmt->bind_param("d", $new_threshold);
        
        if ($stmt->execute()) {
            http_response_code(200); // OK
            echo "Threshold updated successfully to " . $new_threshold . "%";
        } else {
            http_response_code(500); // Internal Server Error
            echo "Error executing statement: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        error_log("Database error in update_co2_threshold.php: " . $e->getMessage());
        http_response_code(500); // Internal Server Error
        echo "A database connection error occurred.";
    }

} else {
    http_response_code(400); // Bad Request
    die("Error: Missing 'co2_threshold' parameter in request.");
}
?>