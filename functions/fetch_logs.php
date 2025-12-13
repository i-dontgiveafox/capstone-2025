<?php

session_start();

// Security check
if (isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
    
    require_once __DIR__ . '/../config/db_conn.php'; 

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed");
    }

    // 1. Get Total Count
    $count_query = "SELECT count(*) as total FROM system_logs";
    $count_result = $conn->query($count_query);
    $total_logs = $count_result->fetch_assoc()['total'] ?? 0;

    // 2. Get Table Rows
    $sql = "SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 50";
    $result = $conn->query($sql);

    ob_start();

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            
            // --- FIX START: Convert UTC to Manila Time ---
            $dt = new DateTime($row['created_at'], new DateTimeZone('UTC'));
            $dt->setTimezone(new DateTimeZone('Asia/Manila'));
            $time = $dt->format("M d, Y • h:i A");
            // --- FIX END ---

            $type = htmlspecialchars($row['event_type']);
            $desc = htmlspecialchars($row['description']);
            $val  = htmlspecialchars($row['sensor_value']);

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

    $table_html = ob_get_clean(); 
    header('Content-Type: application/json');
    echo json_encode(['count' => $total_logs, 'html' => $table_html]);
}
?>