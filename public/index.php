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
    <link rel="stylesheet" href="../index.css">
    

</head>

<body class="bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 bg-fixed min-h-screen overflow-x-hidden">
    <?php include '../includes/navBar.php'; ?>

    <div class="container mx-auto px-2 sm:px-6 lg:px-8">
        <div class="flex flex-col items-start mt-12">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-light">Welcome, <span class="italic font-bold"><?php echo $_SESSION['first_name']; ?></span>!</h1>
        </div>
    </div>

    <!--<div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-start mt-12">
            <div class="px-12 py-4 bg-[#1e1e1e] text-white rounded-2xl">Online</div>
        </div>
    </div>-->

    <!-- 2-column layout: left 40% (overview) and right 60% (sensors) -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-10 gap-6 items-stretch">

            <div class="md:col-span-4">
                <div class="rounded-4xl p-6 relative shadow h-full flex flex-col mb-8">
                    <!-- Glassmorphism overlay -->
                    <div class="absolute inset-0 rounded-4xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.25);"></div>
                    
                    <div class="relative z-10 flex-1">
                        <!-- Background image with dim filter -->
                        <div class="rounded-2xl overflow-hidden h-full bg-cover bg-center relative" style="background-image: url('../assets/images/compost-bg.jpg');">
                            
                            <!-- Dim overlay -->
                            <div class="absolute inset-0 bg-black/40"></div>
                            
                            <!-- Content layer -->
                            <div class="relative z-10 p-6 sm:p-8 md:p-10 h-full text-white">
                                <!-- Online/Offline Status -->
                                <div class="absolute top-6 left-6 sm:top-8 sm:left-8 md:top-10 md:left-10">
                                    <h3 class="text-2xl font-semibold drop-shadow-lg">
                                        <i id="esp-icon" class='bx bx-no-signal text-red-600 p-1'></i>
                                        <span id="esp-status" class="text-red-600">Offline</span>
                                    </h3>

                                </div>

                                <div class="absolute top- left-6 sm:top-8 sm:left-8 md:top-10 md:left-10">
                                    <h3 class="text-2xl font-semibold drop-shadow-lg"></h3>
                                </div>

                                <!-- IP Address -->
                                <div class="absolute bottom-6 right-6 sm:bottom-8 sm:right-8 md:bottom-10 md:right-10">
                                    <p class="text-lg font-medium drop-shadow-md">IP Address:</p>
                                </div>

                                <br><br><br>
                                <div class="text-gray-600">Last Sync:</div>
                                <div id="mc-last-sync" class="font-medium">--:--</div>
                                <div class="text-gray-600">IP Address:</div>
                                <div id="mc-ip" class="font-medium">0.0.0.0</div>
                                <div class="text-gray-600">Uptime:</div>
                                <div id="mc-uptime" class="font-medium">0s</div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <!-- Right: Sensor Cards (approx 60%) -->
            <div class="md:col-span-6 h-full flex flex-col">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-4xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-4xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.18);"></div>
                        <div class="relative z-10 h-50 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bxs-thermometer bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Temperature</h5>
                                    <h6 class="text-xs sm:text-sm md:text-base text-gray-600">Last Update: <span id="temp-last-update">--</span></h6>
                                </div>
                            </div>
                            <a href="#" data-sensor="temperature" class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
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
                                    <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Humidity</h5>
                                    <h6 class="text-xs sm:text-sm md:text-base text-gray-600">Last Update: <span id="humidity-last-update">--</span></h6>
                                </div>
                            </div>
                            <a href="#" data-sensor="humidity" class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
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
                                    <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Moisture</h5>
                                    <h6 class="text-xs sm:text-sm md:text-base text-gray-600">Last Update: <span id="moisture-last-update">--</span></h6>
                                </div>
                            </div>
                            <a href="#" data-sensor="moisture" class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
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
                                    <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Methane</h5>
                                    <h6 class="text-xs sm:text-sm md:text-base text-gray-600">Last Update: <span id="methane-last-update">--</span></h6>
                                </div>
                            </div>
                            <a href="#" data-sensor="methane" class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="md:col-span-4">
                <div class="rounded-4xl p-6 relative shadow h-full flex flex-col mb-8">
                    <div class="absolute inset-0 rounded-4xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.25);"></div>
                    <div class="relative z-10 flex-1">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-normal mb-1">Microcontroller Overview</h3>
                        <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm md:text-base text-gray-800">
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
            </div>-->

    <?php include '../includes/footer.php'; ?>

    <!--<h3>Email: <i><?php // echo $_SESSION['email']; ?>!</i></h3>-->
    <!--<div class="container mx-auto mt-10 p-4 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Welcome to MySite</h1>
        <p class="mb-4">This is a sample page using Tailwind CSS for styling.</p>
    </div>-->
</body>

</html>
<script>
async function fetchESPStatus() {
  try {
    const response = await fetch("../functions/get_esp_status.php");
    const data = await response.json();

    const statusEl = document.getElementById("esp-status");
    const iconEl = document.getElementById("esp-icon");

    if (data.status === "online") {
      statusEl.textContent = "Online";
      statusEl.classList.remove("text-red-600");
      statusEl.classList.add("text-green-600");

      iconEl.classList.remove("bx-no-signal", "text-red-600");
      iconEl.classList.add("bx-signal-5", "text-green-600");
    } else if (data.status === "offline") {
      statusEl.textContent = "Offline";
      statusEl.classList.remove("text-green-600");
      statusEl.classList.add("text-red-600");

      iconEl.classList.remove("bx-signal-5", "text-green-600");
      iconEl.classList.add("bx-no-signal", "text-red-600");
    } else {
      statusEl.textContent = "Unknown";
      statusEl.classList.remove("text-green-600", "text-red-600");
      statusEl.classList.add("text-gray-600");
    }

   // Update last sync time
const syncEl = document.getElementById("mc-last-sync");
if (data.last_seen) {
  // The backend already sends time in Asia/Manila timezone
  const date = new Date(data.last_seen.replace(" ", "T")); // convert to ISO format
  syncEl.textContent = date.toLocaleString("en-PH", {
    timeZone: "Asia/Manila",
    hour12: true,
  });
} else {
  syncEl.textContent = "--:--";
}


  } catch (error) {
    console.error("Error fetching ESP32 status:", error);
  }
}

// Run immediately, then refresh every 10 seconds
fetchESPStatus();
setInterval(fetchESPStatus, 10000);
</script>

    

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