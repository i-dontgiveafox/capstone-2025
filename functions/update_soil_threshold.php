<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "esp-data";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['threshold'])) {
    $new_threshold = $conn->real_escape_string($_POST['threshold']);

    // Make sure there’s always 1 record (id = 1)
    $sql = "UPDATE soil_threshold SET threshold_value='$new_threshold' WHERE id=1";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "threshold" => $new_threshold]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update threshold: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No threshold value provided"]);
}

$conn->close();
?>
