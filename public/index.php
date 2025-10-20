<?php 
session_start();
if (isset($_SESSION['email']) && 
    isset($_SESSION['user_id'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Boxicons CSS (preferred) -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
    <!-- (removed duplicate boxicons script; CSS is used for icons) -->

</head>

<body class="bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 h-screen overflow-x-hidden">
    <?php include '../includes/navBar.php'; ?>

    <div class="flex flex-col justify-start items-center mt-12 px-6 md:mx-20 text-4xl md:text-5xl font-light">
        <h2>Welcome, <span class="italic font-bold"><?php echo $_SESSION['first_name']; ?></span>!</h2>
    </div>
    
    <!-- Two-column grid: left 40%, right 60% -->
    <div class="container mx-auto px-6 mt-8">
    <div class="grid grid-cols-1 md:grid-cols-10 gap-6 items-stretch">
            <!-- left: span 4/10 => 40% on md+ (glass/frosted effect) -->
            <div class="md:col-span-4 rounded-4xl p-6 relative shadow h-full flex flex-col">
                <!-- translucent background + backdrop blur (behind content) -->
                <div class="absolute inset-0 rounded-4xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.25);"></div>
                <div class="relative z-10 flex-1">
                    <h3 class="text-xl font-normal mb-1"><?php //echo $_SESSION['first_name']; ?>Microcontroller Overview</h3>
                    <p class="text-sm text-gray-700 mb-4">This area occupies ~40% of the width on medium+ screens. Put navigation, stats, or summary cards here.</p>

                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-800">
                        <div class="text-gray-600">Name:</div>
                        <div id="mc-name" class="font-medium">--:--</div>

                        <div class="text-gray-600">Status:</div>
                        <div id="mc-status" class="font-medium">Offline</div>

                        <div class="text-gray-600">Last Sync:</div>
                        <div id="mc-last-sync" class="font-medium">--:--</div>

                        <div class="text-gray-600">IP Address:</div>
                        <div id="mc-ip" class="font-medium">0.0.0.0</div>

                        <div class="text-gray-600">Uptime:</div>
                        <div id="mc-uptime" class="font-medium">0s</div>

                        <div class="text-gray-600">Wi-Fi Strength:</div>
                        <div id="mc-wifi" class="font-medium">--%</div>
                    </div>
                </div>
            </div>

            <!-- right: span 6/10 => 60% on md+ -->
            <div class="md:col-span-6 h-full flex flex-col">
                <!--<h3 class="text-xl font-semibold mb-3">Right column</h3>
                <p class="text-sm text-gray-700">This area occupies ~60% of the width on medium+ screens. Use this for charts, feed, or main content.</p>-->

                <!-- 4 responsive cards: stack on xs, 2 columns on sm+ -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
                    <div class="rounded-4xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-4xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.18);"></div>
                        <div class="relative z-10 h-50 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bxs-thermometer bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <div class="text-xl font-semibold">Temperature</div>
                                    <div class="text-sm text-gray-600">Last Update: <span id="temp-last-update">--</span></div>
                                </div>
                            </div>
                            <a href="#" data-sensor="temperature" class="mt-auto self-end text-sm text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                    <div class="rounded-4xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-4xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.18);"></div>
                        <div class="relative z-10 h-50 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bx-water bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <div class="text-xl font-semibold">Humidity</div>
                                    <div class="text-sm text-gray-600">Last Update: <span id="humidity-last-update">--</span></div>
                                </div>
                            </div>
                            <a href="#" data-sensor="humidity" class="mt-auto self-end text-sm text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                    <div class="rounded-4xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-4xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 1);"></div>
                        <div class="relative z-10 h-50 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bx-droplet bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <div class="text-xl font-semibold">Moisture</div>
                                    <div class="text-sm text-gray-600">Last Update: <span id="moisture-last-update">--</span></div>
                                </div>
                            </div>
                            <a href="#" data-sensor="moisture" class="mt-auto self-end text-sm text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                    <div class="rounded-4xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-4xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 1);"></div>
                        <div class="relative z-10 h-50 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bx-wind bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <div class="text-xl font-semibold">Methane</div>
                                    <div class="text-sm text-gray-600">Last Update: <span id="methane-last-update">--</span></div>
                                </div>
                            </div>
                            <a href="#" data-sensor="methane" class="mt-auto self-end text-sm text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<h3>Email: <i><?php // echo $_SESSION['email']; ?>!</i></h3>-->
    <!--<div class="container mx-auto mt-10 p-4 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Welcome to MySite</h1>
        <p class="mb-4">This is a sample page using Tailwind CSS for styling.</p>
    </div>-->
    
</body>

</html>

<script>
    // handle "View chart" clicks
    document.addEventListener('click', function (e) {
        const link = e.target.closest && e.target.closest('.view-chart-link');
        if (!link) return;
        e.preventDefault();
        const sensor = link.dataset.sensor;
        // navigate to sensor detail (placeholder page)
        window.location.href = `sensor.php?sensor=${encodeURIComponent(sensor)}`;
    });
</script>

<?php } else {
    $errorM = "Login First!";
    header("Location: ../public/login.php?error=$errorM");

} ?>