<?php
require_once __DIR__ . '/../config/db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['temp_threshold'])) {
    $new_threshold = filter_var($_POST['temp_threshold'], FILTER_VALIDATE_INT);

    if ($new_threshold === false || $new_threshold < 1 || $new_threshold > 100) {
        http_response_code(400); // Bad Request
        die("Error: Invalid Temperature threshold value provided.");
    }

    try {
        // Update the value for the single row (ID 1) in the temp_threshold table
        $sql = "UPDATE temp_threshold SET value = ? WHERE id = 1";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $new_threshold);
        
        if ($stmt->execute()) {
            http_response_code(200); // OK
            echo "Temperature threshold updated successfully to " . $new_threshold . "%";
        } else {
            http_response_code(500); // Internal Server Error
            echo "Error executing statement: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        error_log("Database error in update_temp_threshold.php: " . $e->getMessage());
        http_response_code(500); // Internal Server Error
        echo "A database connection error occurred.";
    }

} else {
    http_response_code(400); // Bad Request
    die("Error: Missing 'temp_threshold' parameter in request.");
}
?>