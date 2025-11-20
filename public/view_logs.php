<?php
// Tiyakin na nag-start ang session sa pinakauna
session_start();

// Ginamit natin ang session check galing sa index.php
if (isset($_SESSION['email']) && 
    isset($_SESSION['user_id'])) {

    // Database Connection (nasa loob ng session check)
    require_once __DIR__ . '/../config/db.php';

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

    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 bg-fixed overflow-x-hidden text-gray-800">

    <?php include '../includes/navBar.php'; ?>

    <main class="flex-grow pt-24 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-7xl mx-auto">
            
            <!-- Header & Stats Block -->
            <div class="bg-white/80 backdrop-blur-md rounded-3xl p-6 mb-8 shadow-lg flex flex-col md:flex-row justify-between items-center gap-4 border border-white/40">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                        <i class='bx bxs-report text-green-600'></i> System Activity Logs
                    </h1>
                    <p class="text-gray-600 mt-1 ml-1">History of automated actions (Water Pump & Exhaust Fan)</p>
                </div>

                <!-- Total Events Counter -->
                <div class="flex items-center gap-4">
                    <div class="bg-green-50 px-5 py-3 rounded-2xl border border-green-200 flex flex-col items-center shadow-sm">
                        <span class="text-xs font-bold text-green-600 uppercase tracking-wide">Total Events</span>
                        <span class="text-3xl font-extrabold text-green-700 leading-none mt-1">
                            <?php 
                            // Count logs in database
                            $count_query = "SELECT count(*) as total FROM system_logs";
                            $count_result = $conn->query($count_query);
                            $total_logs = $count_result->fetch_assoc()['total'];
                            echo $total_logs ?? 0;
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Logs Table Card -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl overflow-hidden mb-10 border border-white/50">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-[#1e1e1e] text-white text-left text-xs font-semibold uppercase tracking-wider">
                                <th class="px-6 py-4 rounded-tl-lg">Timestamp</th>
                                <th class="px-6 py-4">Device Type</th>
                                <th class="px-6 py-4">Event Description</th>
                                <th class="px-6 py-4 text-center rounded-tr-lg">Sensor Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            // KUNIN ANG LOGS: Latest ang nasa taas (DESC)
                            $sql = "SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 50";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    // Format Time
                                    $time = date("M d, Y • h:i A", strtotime($row['created_at']));
                                    $type = htmlspecialchars($row['event_type']);
                                    $desc = htmlspecialchars($row['description']);
                                    $val  = htmlspecialchars($row['sensor_value']);

                                    // Styling Logic: Iba ang kulay kapag PUMP vs FAN
                                    $badgeClass = "bg-gray-100 text-gray-600";
                                    $icon = "bx-chip";
                                    $rowClass = "hover:bg-gray-50";

                                    // Water Pump (Blue Theme)
                                    if (stripos($type, 'Pump') !== false) {
                                        $badgeClass = "bg-blue-100 text-blue-800 border border-blue-200";
                                        $icon = "bx-water";
                                        $rowClass = "hover:bg-blue-50";
                                    } 
                                    // Exhaust Fan (Orange Theme)
                                    elseif (stripos($type, 'Fan') !== false) {
                                        $badgeClass = "bg-orange-100 text-orange-800 border border-orange-200";
                                        $icon = "bx-wind";
                                        $rowClass = "hover:bg-orange-50";
                                    }

                                    echo "<tr class='$rowClass transition duration-150'>";
                                    
                                    // Time Column
                                    echo "<td class='px-6 py-4 text-sm font-mono text-gray-500 whitespace-nowrap'>
                                            $time
                                          </td>";
                                    
                                    // Device Column
                                    echo "<td class='px-6 py-4'>
                                            <span class='inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase shadow-sm $badgeClass'>
                                                <i class='bx $icon text-sm'></i> $type
                                            </span>
                                          </td>";
                                    
                                    // Description Column
                                    echo "<td class='px-6 py-4 text-sm text-gray-800 font-medium'>
                                            $desc
                                          </td>";
                                    
                                    // Value Column
                                    echo "<td class='px-6 py-4 text-center'>
                                            <span class='inline-block bg-white text-gray-900 text-sm font-bold px-3 py-1 rounded-lg border border-gray-300 font-mono shadow-sm'>
                                                $val
                                            </span>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                // Kung walang logs
                                echo "<tr><td colspan='4' class='px-6 py-16 text-center text-gray-400 italic'>
                                        <div class='flex flex-col items-center justify-center'>
                                            <i class='bx bx-history text-5xl mb-2 opacity-30'></i>
                                            <span>No activity logs found yet.</span>
                                            <span class='text-xs mt-1'>Logs will appear here when sensors trigger the pump or fan.</span>
                                        </div>
                                      </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- Auto Refresh Script (Updates table every 10 seconds) -->
    <script>
        setTimeout(() => {
           window.location.reload();
        }, 10000);
    </script>

</body>
</html>

<?php 
// Ito ang else block para sa session check. 
// Kung walang session, i-redirect sa login page.
} else {
    $errorM = "Login First!";
    header("Location: ../public/login.php?error=$errorM");
}
?>