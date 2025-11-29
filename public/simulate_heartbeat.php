<?php
// functions/simulate_heartbeat.php

// 1. Database Connection
require '../config/db_conn.php'; // Or use your manual connection lines if you prefer

if (!isset($conn)) {
    $conn = new mysqli($servername, $username, $password, $dbname);
}

// 2. Set Timezone
date_default_timezone_set('Asia/Manila');

$msg = "";

// ➤ ACTION: ONLINE (Insert Current Time)
if (isset($_GET['status']) && $_GET['status'] == 'online') {
    // We match your screenshot: id=2, device_name='esp32_1'
    $sql = "INSERT INTO heartbeat_data (id, device_name, last_seen) 
            VALUES (2, 'esp32_1', NOW())";
            
    if ($conn->query($sql)) {
        $msg = "✅ <b>ONLINE sent!</b> <br>Time: " . date('h:i:s A');
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}

// ➤ ACTION: OFFLINE (Insert Time from 5 mins ago)
if (isset($_GET['status']) && $_GET['status'] == 'offline') {
    // We insert a time in the past so the dashboard calculates > 60 seconds diff
    $sql = "INSERT INTO heartbeat_data (id, device_name, last_seen) 
            VALUES (2, 'esp32_1', DATE_SUB(NOW(), INTERVAL 5 MINUTE))";
            
    if ($conn->query($sql)) {
        $msg = "🚫 <b>OFFLINE sent!</b> <br>Time set to 5 mins ago.";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heartbeat Test</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background: #f0fdf4; }
        .box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 400px; margin: auto; }
        .btn { display: block; width: 100%; padding: 15px; margin: 10px 0; text-decoration: none; color: white; border-radius: 8px; font-weight: bold; font-size: 18px; }
        .green { background: #10b981; } .green:hover { background: #059669; }
        .red { background: #ef4444; } .red:hover { background: #b91c1c; }
        .status { margin-bottom: 20px; font-size: 1.2rem; }
    </style>
</head>
<body>
    <div class="box">
        <h2>💓 Heartbeat Control</h2>
        
        <?php if($msg): ?>
            <div class="status"><?php echo $msg; ?></div>
        <?php else: ?>
            <p style="color:gray">Click a button to update database.</p>
        <?php endif; ?>

        <a href="?status=online" class="btn green">⚡ Set Status ONLINE</a>
        <a href="?status=offline" class="btn red">💀 Set Status OFFLINE</a>
    </div>
</body>
</html>