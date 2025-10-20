<?php
include 'config/db_conn.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT COUNT(*) as total FROM SensorData";
$totalResult = $conn->query($sql);
$totalRows = $totalResult ? $totalResult->fetch(PDO::FETCH_ASSOC)['total'] : 0;
$totalPages = ceil($totalRows / $limit);

$sql = "SELECT id, sensor, location, value1, value2, value3, reading_time FROM SensorData ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
function renderTable($result, $page, $totalPages) {
    ob_start();
    ?>
    <table class="min-w-full table-auto text-left text-gray-800">
        <thead>
            <tr class="bg-[#CCEBD5]/60">
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Sensor</th>
                <th class="px-4 py-2">Location</th>
                <th class="px-4 py-2">Value1</th>
                <th class="px-4 py-2">Value2</th>
                <th class="px-4 py-2">Value3</th>
                <th class="px-4 py-2">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='border-b border-gray-200'>"
                        ."<td class='px-4 py-2'>".htmlspecialchars($row["id"])."</td>"
                        ."<td class='px-4 py-2'>".htmlspecialchars($row["sensor"])."</td>"
                        ."<td class='px-4 py-2'>".htmlspecialchars($row["location"])."</td>"
                        ."<td class='px-4 py-2'>".htmlspecialchars($row["value1"])."</td>"
                        ."<td class='px-4 py-2'>".htmlspecialchars($row["value2"])."</td>"
                        ."<td class='px-4 py-2'>".htmlspecialchars($row["value3"])."</td>"
                        ."<td class='px-4 py-2'>".htmlspecialchars($row["reading_time"])."</td>"
                        ."</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='px-4 py-6 text-center text-gray-500'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <!-- Pagination -->
    <div class="flex justify-between items-center mt-6">
        <div class="text-sm text-gray-600">
            Page <?php echo $page; ?> of <?php echo $totalPages; ?>
        </div>
        <div class="flex gap-2">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page-1; ?>" class="px-4 py-2 rounded bg-[#B6FC67] text-black hover:bg-[#87acec] pagination-link">&larr; Prev</a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page+1; ?>" class="px-4 py-2 rounded bg-[#B6FC67] text-black hover:bg-[#87acec] pagination-link">Next &rarr;</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    echo renderTable($result, $page, $totalPages);
    $conn = null;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESP32 Sensor Readings</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Boxicons CSS (preferred) -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../assets/icons/worm.png">

</head>
<body class="bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 min-h-screen overflow-x-hidden">
    
    <?php include 'includes/navBar.php'; ?>

    <div class="container mx-auto px-6 md:px-24 mt-16">
        <h2 class="text-3xl md:text-4xl font-light mb-8">ESP32 Sensor Readings</h2>
        <div class="rounded-4xl p-6 relative shadow border border-white/20 bg-white/60 backdrop-blur mb-8">
            <div id="sensor-table">
                <?php echo renderTable($result, $page, $totalPages); ?>
            </div>
        </div>
    </body>
    <script>
    // AJAX pagination
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination-link');
        if (!link) return;
        e.preventDefault();
        const url = link.getAttribute('href') + '&ajax=1';
        fetch(url)
            .then(res => res.text())
            .then(html => {
                document.getElementById('sensor-table').innerHTML = html;
                window.history.pushState({}, '', link.getAttribute('href'));
            });
    });
    </script>
    </html>