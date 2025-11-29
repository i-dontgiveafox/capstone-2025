<?php
session_start();

// Check login
if (isset($_SESSION['email']) && isset($_SESSION['user_id'])) {

    require_once __DIR__ . '/../config/db_conn.php'; 

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Activity Logs</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
    
    <link rel="stylesheet" href="../index.css">
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 bg-fixed overflow-x-hidden text-gray-800">

    <?php include '../includes/navBar.php'; ?>

    <main class="flex-grow pt-20 px-4 sm:px-6 lg:px-8"> 
        
        <div class="max-w-7xl mx-auto">
            
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 mb-6 shadow-lg flex flex-col md:flex-row justify-between items-center gap-4 border border-white/40">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class='bx bxs-report text-green-600'></i> System Activity Logs
                    </h1>
                    <p class="text-sm text-gray-600 mt-1 ml-1">History of automated actions</p>
                </div>

                <div class="bg-green-50/80 px-4 py-2 rounded-xl border border-green-100 flex flex-col items-center">
                    <span class="text-[10px] font-bold text-green-600 uppercase tracking-wide">Total Events</span>
                    <span class="text-2xl font-bold text-green-700 leading-none">
                        <?php 
                        $count_query = "SELECT count(*) as total FROM system_logs";
                        $count_result = $conn->query($count_query);
                        $total_logs = $count_result->fetch_assoc()['total'];
                        echo $total_logs ?? 0;
                        ?>
                    </span>
                </div>
            </div>

            <div class="bg-transparent md:bg-white/90 md:backdrop-blur-sm md:rounded-2xl md:shadow-xl md:border md:border-white/50 overflow-hidden mb-10">
                
                <table class="min-w-full leading-normal">
                    <thead class="hidden md:table-header-group">
                        <tr class="bg-[#1e1e1e] text-white text-left text-xs font-semibold uppercase tracking-wider">
                            <th class="px-6 py-4 rounded-tl-lg">Timestamp</th>
                            <th class="px-6 py-4">Device Type</th>
                            <th class="px-6 py-4">Event Description</th>
                            <th class="px-6 py-4 text-center rounded-tr-lg">Sensor Value</th>
                        </tr>
                    </thead>

                    <tbody class="block md:table-row-group space-y-4 md:space-y-0">
                        <?php
                        $sql = "SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 50";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $time = date("M d, Y • h:i A", strtotime($row['created_at']));
                                $type = htmlspecialchars($row['event_type']);
                                $desc = htmlspecialchars($row['description']);
                                $val  = htmlspecialchars($row['sensor_value']);

                                // Style Logic
                                $badgeClass = "bg-gray-100 text-gray-600";
                                $icon = "bx-chip";
                                if (stripos($type, 'Pump') !== false) {
                                    $badgeClass = "bg-blue-100 text-blue-800 border border-blue-200";
                                    $icon = "bx-water";
                                } elseif (stripos($type, 'Fan') !== false) {
                                    $badgeClass = "bg-orange-100 text-orange-800 border border-orange-200";
                                    $icon = "bx-wind";
                                }
                        ?>
                            <tr class="bg-white/90 backdrop-blur-sm flex flex-col md:table-row rounded-xl shadow-lg md:shadow-none border border-white/50 md:border-b md:border-gray-200 p-4 md:p-0 relative">
                                
                                <td class="md:px-6 md:py-4 order-1 md:order-none flex justify-between md:table-cell">
                                    <span class="text-xs font-bold text-gray-400 uppercase md:hidden">Time</span>
                                    <span class="text-xs md:text-sm font-mono text-gray-500"><?php echo $time; ?></span>
                                </td>

                                <td class="md:px-6 md:py-4 order-3 md:order-none mt-3 md:mt-0 block md:table-cell">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase shadow-sm w-full md:w-auto justify-center md:justify-start <?php echo $badgeClass; ?>">
                                        <i class='bx <?php echo $icon; ?> text-sm'></i> <?php echo $type; ?>
                                    </span>
                                </td>

                                <td class="md:px-6 md:py-4 order-2 md:order-none mt-2 md:mt-0 block md:table-cell">
                                    <span class="text-sm text-gray-800 font-medium block leading-snug">
                                        <?php echo $desc; ?>
                                    </span>
                                </td>

                                <td class="md:px-6 md:py-4 text-center order-4 md:order-none mt-3 md:mt-0 flex justify-between items-center md:table-cell">
                                    <span class="text-xs font-bold text-gray-400 uppercase md:hidden">Value</span>
                                    <span class="inline-block bg-gray-100 text-gray-900 text-sm font-bold px-3 py-1 rounded-lg border border-gray-300 font-mono shadow-sm">
                                        <?php echo $val; ?>
                                    </span>
                                </td>
                            </tr>

                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='4' class='px-6 py-10 text-center text-gray-500 italic'>No logs found.</td></tr>";
                        } 
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

<?php include '../includes/footer.php'; ?>
</body>
</html>

<?php 
} else {
    $errorM = "Login First!";
    header("Location: ../public/login.php?error=$errorM");
}
?>