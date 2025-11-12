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

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
    <link rel="stylesheet" href="../index.css">
    

</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 bg-fixed overflow-x-hidden">
    <?php include '../includes/navBar.php'; ?>

    <main class="flex-grow pt-16">
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
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-10">
        <div class="bg-[url('../assets/img/polygon-bg.jpg')] bg-cover bg-center border-solid rounded-3xl p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-10 gap-6 items-stretch">
            <div class="md:col-span-3">
                <div class="rounded-xl overflow-hidden shadow h-full flex flex-col mb-8">
                    <div class="relative h-full">
                        <!-- Background image with dim filter -->
                        <div class="h-full bg-cover bg-center relative" style="background-image: url('../assets/images/compost-bg.jpg');">
                            
                            <!-- Dim overlay -->
                            <div class="absolute inset-0 bg-black/40"></div>
                            
                            <!-- Content layer -->
                            <div class="relative z-5 p-6 sm:p-8 md:p-10 h-full text-white">
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

                                <!-- IP Address 
                                <div class="absolute bottom-6 right-6 sm:bottom-8 sm:right-8 md:bottom-10 md:right-10">
                                    <p class="text-lg font-medium drop-shadow-md">IP Address:</p>
                                </div>-->

                                <br><br><br>
                                <div class="text-white-600">Last Sync:</div>
                                <div id="mc-last-sync" class="font-medium">--:--</div>
                                <div class="text-white-600">Uptime:</div>
                                <div id="mc-uptime" class="font-medium">0s</div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <!-- Right: Sensor Cards (approx 60%) -->
            <div class="md:col-span-7 h-full flex flex-col">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 1);"></div>
                        <div class="relative z-10 h-60 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bxs-thermometer bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Temperature</h5>
                                    <span class="text-xs sm:text-sm md:text-base text-gray-600">Last Update: <span id="temp-last"> <?php echo $temp_last; ?></span></span>
                                    
                                </div>
                            </div>
                            <div class="absolute top-35 right-10 text-4xl font-bold text-gray-600 mt-1">
                                <span id="temp-value"></span> 
                                <span class="text-xl text-black/80">°C</span>
                            </div>
                            <!--<a href="#" data-sensor="temperature" class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>-->
                        </div>
                    </div>
                    <div class="rounded-xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 1);"></div>
                        <div class="relative z-10 h-60 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bx-water bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Humidity</h5>
                                    <span class="text-xs sm:text-sm md:text-base text-gray-600">Last Update: <span id="humid-last"> <?php echo $gas_last; ?></span></span>
                                    
                                </div>
                            </div>
                            <div class="absolute top-35 right-10 text-4xl font-bold text-gray-600 mt-1">
                                <span id="humid-value"></span> 
                                <span class="text-xl text-gray-600">%</span>
                            </div>
                            <!--<a href="#" data-sensor="humidity" class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>-->
                        </div>
                    </div>
                    <div class="rounded-xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 1);"></div>
                        <div class="relative z-10 h-60 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bx-droplet bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Moisture</h5>
                                    <span class="text-xs sm:text-sm md:text-base text-gray-600">Last Update: <span id="moist-last"> <?php echo $moist_last; ?></span></span>
                                    
                                </div>
                            </div>
                            <div class="absolute top-35 right-10 text-4xl font-bold text-gray-600 mt-1">
                                <span id="moist-value"></span> 
                                <span class="text-xl text-gray-600">%</span>
                            </div>
                            <!--<a href="#" data-sensor="moisture" class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>-->
                        </div>
                    </div>
                   <!-- 🧪 Ammonia Sensor Card -->
<div class="rounded-xl p-4 relative shadow border border-white/20">
  <div class="absolute inset-0 rounded-xl"
       style="background: rgba(255,255,255,0.18);
              backdrop-filter: blur(6px);
              -webkit-backdrop-filter: blur(6px);
              border: 1px solid rgba(255,255,255, 1);"></div>
  
  <div class="relative z-10 h-60 rounded p-4 flex flex-col items-start justify-start text-gray-800">
    <div class="flex items-center gap-3 w-full">
      <i class='bx bxs-flask bg-white rounded-full p-3'></i>
      <div class="flex-1">
        <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Ammonia (NH₃)</h5>
        <span class="text-xs sm:text-sm md:text-base text-gray-600">
          Last Update: <span id="ammonia-last">--:--</span>
        </span>
      </div>
    </div>

    <!-- Value Display -->
    <div class="absolute top-35 right-10 text-4xl font-bold text-gray-600 mt-1">
      <span id="ammonia-value">--</span>
      <span class="text-xl text-gray-600">ppm</span>
    </div>

    <!-- Optional chart link (commented out) -->
    <!--
    <a href="#" data-sensor="ammonia"
       class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
       View chart <i class='bx bx-right-arrow-alt'></i>
    </a>
    -->
  </div>
</div>





                    <div class="rounded-xl p-4 relative shadow border border-white/20">
                        <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 1);"></div>
                        <div class="relative z-10 h-60 rounded p-4 flex flex-col items-start justify-start text-gray-800">
                            <div class="flex items-center gap-3 w-full">
                                <i class='bx bx-wind bg-white rounded-full p-3'></i>
                                <div class="flex-1">
                                    <h5 class="text-lg sm:text-xl md:text-xl font-semibold">Co2 Gas</h5>
                                    <span class="text-xs sm:text-sm md:text-base text-gray-600">Last Update: <span id="gas-last"> <?php echo $gas_last; ?></span></span>
                                
                                </div>
                                <div class="absolute top-35 right-10 text-4xl font-bold text-gray-600 mt-1">
                                <span id="gas-value"></span> 
                                <span class="text-xl text-gray-600">%</span>
                            </div>
                        </div>
                        
                            
                            <!--<a href="#" data-sensor="methane" class="mt-auto self-end text-xs sm:text-sm md:text-base text-[#0f5132] hover:underline flex items-center gap-2 view-chart-link">
                                View chart <i class='bx bx-right-arrow-alt'></i>
                            </a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    </main>
    
    <!-- Daily Overview Section -->
    <section class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800/90 mb-4 mt-10 px-4">Daily Overview</h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Soil Moisture -->
            <div class="relative bg-[url('../assets/images/overview-bg-green.jpg')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
            <!-- Gradient overlay -->
            <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>

            <!-- Content -->
                <div class="flex flex-col space-y-2 relative z-10">
                    <h3 class="text-lg font-semibold text-white"><i class='bx bxs-droplet'></i> Avg. Soil Moisture</h3>
                    <div class="flex items-baseline space-x-2">
                    <span class="text-4xl font-bold text-white" id="avg-moisture">35</span>
                    <span class="text-xl text-white/80">%</span>
                    </div>
                </div>
            </div>


            <!-- Temperature -->
            <div class="relative bg-[url('../assets/images/overview-bg-yellow.png')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
            <!-- Gradient overlay -->
            <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>

            <!-- Content -->
                <div class="flex flex-col space-y-2 relative z-10">
                    <h3 class="text-lg font-semibold text-white"><i class='bx bxs-droplet'></i> Avg. Temperature</h3>
                    <div class="flex items-baseline space-x-2">
                    <span class="text-4xl font-bold text-white" id="avg-moisture">35</span>
                    <span class="text-xl text-white/80">°C</span>
                    </div>
                </div>
            </div>
            


            <!-- Humidity -->
            <div class="relative bg-[url('../assets/images/overview-bg-orange.png')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
            <!-- Gradient overlay -->
            <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>

            <!-- Content -->
                <div class="flex flex-col space-y-2 relative z-10">
                    <h3 class="text-lg font-semibold text-white"><i class='bx bxs-droplet'></i> Avg. Humidity</h3>
                    <div class="flex items-baseline space-x-2">
                    <span class="text-4xl font-bold text-white" id="avg-moisture">%</span>
                    <span class="text-xl text-white/80">°C</span>
                    </div>
                </div>
            </div>

            <!-- Water Usage -->
            <div class="relative bg-[url('../assets/images/overview-bg-blue.png')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
            <!-- Gradient overlay -->
            <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/40 to-transparent pointer-events-none"></div>

            <!-- Content -->
                <div class="flex flex-col space-y-2 relative z-10">
                    <h3 class="text-lg font-semibold text-white"><i class='bx bxs-droplet'></i> Water Level</h3>
                    <div class="flex items-baseline space-x-2">
                    <span class="text-4xl font-bold text-white" id="avg-moisture">%</span>
                    <span class="text-xl text-white/80">mL</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Grid Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <!-- Left Chart Placeholder -->
            <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Moisture Trend</h3>
            <div class="w-full h-82 bg-gray-100 rounded-2xl flex items-center justify-center">
                <canvas id="moistureChart" class="w-full h-full"></canvas>
            </div>
            </div>

            <!-- Right Chart Placeholder -->
            <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Water Usage Distribution</h3>
            <div id="chart-water" class="w-full h-82 bg-gray-100 rounded-2xl flex items-center justify-center">
                <canvas id="waterUsageChart" class="w-full h-full"></canvas>
            </div>
            </div>
        </div>

        <!-- Chart Grid Section -->
        <div class="grid grid-cols-1 grid-cols-1 gap-6 mb-10">
            <!-- Left Chart Placeholder -->
            <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Temperature and Humidity Trend</h3>
            <div class="w-full h-156 w-full bg-gray-100 rounded-2xl flex items-center justify-center">
                <canvas id="tempHumChart" class="w-full h-full"></canvas>
            </div>
            </div>
        </div>
    </section>


    <section class="container mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-10">
        <div class="bg-[url('../assets/img/polygon-bg.jpg')] bg-cover bg-center rounded-3xl p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Control Panel</h2>
        </div>

        <!-- Control Toggles -->
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
            <!-- Fan Control -->
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>
                <div class="relative z-10 rounded p-4 flex items-center justify-between text-gray-800">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="flex-shrink-0">
                            <img src="../assets/icons/fan.png" alt="Fan Icon" class="rounded-full p-2 bg-white w-10 h-10 object-contain" />
                        </div>
                        <h3 class="text-lg font-semibold truncate">Fan</h3>
                    </div>
                    <label class="switch relative inline-block w-14 h-8 flex-shrink-0 ml-4">
                        <input type="checkbox" id="fanToggle" class="peer hidden">
                        <span class="slider absolute inset-0 bg-gray-400 rounded-full transition peer-checked:bg-green-500"></span>
                        <span class="dot absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition peer-checked:translate-x-6"></span>
                    </label>
                </div>
            </div>

            <!-- Sprinkler Control -->
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>
                <div class="relative z-10 rounded p-4 flex items-center justify-between text-gray-800">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="flex-shrink-0">
                            <img src="../assets/icons/fan.png" alt="Sprinkler Icon" class="rounded-full p-2 bg-white w-10 h-10 object-contain" />
                        </div>
                        <h3 class="text-lg font-semibold truncate">Sprinkler</h3>
                    </div>
                    <label class="switch relative inline-block w-14 h-8 flex-shrink-0 ml-4">
                        <input type="checkbox" id="sprinklerToggle" class="peer hidden">
                        <span class="slider absolute inset-0 bg-gray-400 rounded-full transition peer-checked:bg-green-500"></span>
                        <span class="dot absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition peer-checked:translate-x-6"></span>
                    </label>
                </div>
            </div>

            <!-- Sieving Control 
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>
                <div class="relative z-10 rounded p-4 flex items-center justify-between text-gray-800">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="flex-shrink-0">
                            <img src="../assets/icons/fan.png" alt="Sieve Icon" class="rounded-full p-2 bg-white w-10 h-10 object-contain" />
                        </div>
                        <h3 class="text-lg font-semibold truncate">Sieving</h3>
                    </div>
                    <label class="switch relative inline-block w-14 h-8 flex-shrink-0 ml-4">
                        <input type="checkbox" id="sievingToggle" class="peer hidden">
                        <span class="slider absolute inset-0 bg-gray-400 rounded-full transition peer-checked:bg-green-500"></span>
                        <span class="dot absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition peer-checked:translate-x-6"></span>
                    </label>
                </div>
            </div>-->
        </div>

        <div class="flex flex-col items-start justify-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800/90 px-4 mb-4 mt-10">Set Durations and Thresholds</h2>
            <button id="resetSettingsBtn" class="bg-[#1e1e1e] text-white px-2 pr-4 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full sm:w-auto sm:ml-auto">
                <i class='bx bx-refresh px-2 py-3'></i>Reset
            </button>
        </div>

        <!-- Reset Settings confirmation modal (hidden by default) -->
        <div id="reset-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 bg-opacity-20">
            <div class="bg-white rounded-lg p-6 w-11/12 max-w-md">
                <h3 class="text-lg font-semibold mb-2 text-gray-900">Confirm Reset</h3>
                <p class="text-sm text-gray-700 mb-4">Are you sure you want to reset your settings? This will restore the threshold and duration to default values.</p>
                <div class="flex justify-end gap-3">
                    <button id="cancel-reset" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800">Cancel</button>
                    <button id="confirm-reset" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white">Reset Settings</button>
                </div>
            </div>
        </div>
<!-- Settings Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-5">

  <!-- 🌱 Moisture Threshold -->
  <div class="rounded-xl p-4 relative shadow border border-white/20">
    <div class="absolute inset-0 rounded-xl"
      style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>

    <div class="relative z-10 p-4 flex flex-col h-full">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Moisture Level Settings</h3>

      <div class="flex-grow">
        <label class="block text-sm text-gray-600 mb-2">Trigger Threshold (%)</label>
        <div class="relative w-full">
          <select id="thresholdSelect" class="w-full p-2 pr-10 border rounded-lg bg-white/80 appearance-none">
            <option value="55">55%</option>
            <option value="60">60%</option>
            <option value="65">65%</option>
            <option value="70">70%</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-2 mt-4">
        <button id="saveThresholdBtn"
          class="bg-[#1e1e1e] text-white px-4 py-2 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full sm:w-auto">
          Save Threshold
        </button>
        <button id="resetThresholdBtn"
          class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition w-full sm:w-auto">
          Reset Default (55%)
        </button>
      </div>

      <!-- Fixed padding area for notification -->
      <div class="h-6 mt-3 flex items-center justify-center">
        <p id="statusMsg" class="text-sm text-center whitespace-nowrap"></p>
      </div>
    </div>
  </div>

  <!-- 💧 Sprinkler Duration -->
  <div class="rounded-xl p-4 relative shadow border border-white/20">
    <div class="absolute inset-0 rounded-xl"
      style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>

    <div class="relative z-10 p-4 flex flex-col h-full">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Sprinkler Duration</h3>

      <div class="flex-grow">
        <label class="block text-sm text-gray-600 mb-2">Set Duration</label>
        <div class="relative w-full">
          <select id="durationSelect" class="w-full p-2 pr-10 border rounded-lg bg-white/80 appearance-none">
            <option value="2000" selected>2 seconds</option>
            <option value="2500">2.5 seconds</option>
            <option value="3000">3 seconds</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-2 mt-4">
        <button id="saveDuration"
          class="bg-[#1e1e1e] text-white px-4 py-2 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full sm:w-auto">
          Save Duration
        </button>
        <button id="resetDurationBtn"
          class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition w-full sm:w-auto">
          Reset Default (2 s)
        </button>
      </div>

      <!-- Fixed padding area for notification -->
      <div class="h-6 mt-3 flex items-center justify-center">
        <p id="durationStatusMsg" class="text-sm text-center whitespace-nowrap"></p>
      </div>
    </div>
  </div>

</div>

            <!-- Fan Duration
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>
                <div class="relative z-10 p-4 flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Fan Duration</h3>
                    <div class="flex-grow">
                        <label class="block text-sm text-gray-600 mb-2">Set Duration</label>
                        <select class="w-full p-2 border rounded-lg bg-white/80">
                            <option>10 seconds</option>
                            <option>20 seconds</option>
                            <option>30 seconds</option>
                        </select>
                    </div>
                    <button class="mt-4 bg-[#1e1e1e] text-white px-4 py-2 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full sm:w-auto sm:ml-auto">
                        Save Duration
                    </button>
                </div>
            </div>-->

            <!-- Sieving Duration 
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>
                <div class="relative z-10 p-4 flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sieving Duration</h3>
                    <div class="flex-grow">
                        <label class="block text-sm text-gray-600 mb-2">Set Duration (3-10 mins)</label>
                        <input type="text" class="w-full p-2 border rounded-lg bg-white/80" placeholder="Minutes">
                    </div>
                    <button class="mt-4 bg-[#1e1e1e] text-white px-4 py-2 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full sm:w-auto sm:ml-auto">
                        Save Duration
                    </button>
                </div>
            </div> -->       
        </div>
        
    </section>


    <?php include '../includes/footer.php'; ?>

    <!--<h3>Email: <i><?php // echo $_SESSION['email']; ?>!</i></h3>-->
    <!--<div class="container mx-auto mt-10 p-4 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Welcome to MySite</h1>
        <p class="mb-4">This is a sample page using Tailwind CSS for styling.</p>
    </div>-->
</body>

</html>



<script>
function updateAmmonia() {
  fetch('../functions/get_ammonia_data.php')
    .then(response => response.json())
    .then(data => {
      const value = data.ammonia_value ?? '--';
      const last = data.ammonia_last ?? '--';

      document.getElementById('ammonia-value').textContent = value;
      document.getElementById('ammonia-last').textContent = last;

      // Color logic for ammonia
      const ammoniaBox = document.getElementById('ammonia-value');
      if (value === '--') {
        ammoniaBox.style.color = 'gray';
      } else {
        const val = parseFloat(value);
        if (val <= 8) {
          ammoniaBox.style.color = 'gray-700'; // green - safe (ready to harvest)
        } else if (val <= 15) {
          ammoniaBox.style.color = 'gray-700'; // yellow - moderate
        } else {
          ammoniaBox.style.color = ''; // red - high
        }
      }
    })
    .catch(err => console.error('❌ Failed to load ammonia data:', err));
}

</script>









<script>
const resetBtn = document.getElementById("resetSettingsBtn");
const resetModal = document.getElementById("reset-modal");
const cancelReset = document.getElementById("cancel-reset");
const confirmReset = document.getElementById("confirm-reset");

// Open modal
resetBtn.addEventListener("click", () => {
  resetModal.classList.remove("hidden");
});

// Close modal when clicking Cancel or outside
cancelReset.addEventListener("click", () => {
  resetModal.classList.add("hidden");
});
resetModal.addEventListener("click", (e) => {
  if (e.target === resetModal) resetModal.classList.add("hidden");
});

// Confirm reset — resets Moisture Threshold + Sprinkler Duration
confirmReset.addEventListener("click", async () => {
  confirmReset.disabled = true;
  confirmReset.textContent = "Resetting...";

  try {
    // Reset Moisture Threshold (to 55%)
    await fetch("../functions/update_soil_threshold.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "threshold=55"
    });

    // Reset Sprinkler Duration (to 2000 ms / 2s)
    await fetch("../functions/save_sprinkler_duration.php?duration=2000");

    // Update dropdowns visually
    const thresholdSelect = document.getElementById("thresholdSelect");
    const durationSelect = document.getElementById("durationSelect");
    if (thresholdSelect) thresholdSelect.value = "55";
    if (durationSelect) durationSelect.value = "2000";

    // ✅ Toast instead of alert
    showToast("✅ Settings have been reset to default values!", false);

  } catch {
    // ❌ Toast on error
    showToast("❌ Failed to reset settings. Please try again.", true);
  } finally {
    confirmReset.disabled = false;
    confirmReset.textContent = "Reset Settings";
    resetModal.classList.add("hidden");
  }
});

// === TOAST FUNCTION ===
function showToast(message, isError = false) {
  const toast = document.createElement("div");
  toast.textContent = message;
  toast.className = `
    fixed bottom-6 right-6 px-4 py-2 rounded-lg shadow-lg text-white z-50 
    transform translate-y-4 opacity-0 transition-all duration-300 ease-out 
    ${isError ? "bg-red-600" : "bg-green-600"}
  `;
  document.body.appendChild(toast);

  // Animate in
  setTimeout(() => {
    toast.classList.remove("translate-y-4", "opacity-0");
    toast.classList.add("translate-y-0", "opacity-100");
  }, 50);

  // Fade out and remove after 3s
  setTimeout(() => {
    toast.classList.remove("opacity-100");
    toast.classList.add("opacity-0", "translate-y-2");
    setTimeout(() => toast.remove(), 400);
  }, 3000);
}
</script>



<!-- 🎨 Animation + UI Enhancements -->
<style>
#statusMsg, #durationStatusMsg {
  transition: all 0.4s ease;
  opacity: 0;
}
.pulse {
  animation: pulse 0.4s ease;
}
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.08); }
  100% { transform: scale(1); }
}
</style>

<!-- ⚙️ Functionality Script -->
<script>
// Reusable message display with animation
function showMsg(el, msg, success = true) {
  el.textContent = msg;
  el.className = success ? "text-green-600 text-sm pulse" : "text-red-600 text-sm pulse";
  el.style.opacity = 1;
  setTimeout(() => (el.style.opacity = 0), 2000);
}

// ===== 🌱 Moisture Threshold =====
async function loadThreshold() {
  try {
    const res = await fetch("../functions/get_soil_threshold.php");
    const val = (await res.text()).trim();
    document.getElementById("thresholdSelect").value = val;
  } catch {
    console.error("Failed to load threshold.");
  }
}

async function saveThreshold() {
  const val = document.getElementById("thresholdSelect").value;
  const res = await fetch("../functions/update_soil_threshold.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "threshold=" + val
  });
  if (res.ok) showMsg(document.getElementById("statusMsg"), "✅ Threshold saved to " + val + "%");
}

async function resetThreshold() {
  const res = await fetch("../functions/update_soil_threshold.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "threshold=55"
  });
  if (res.ok) {
    document.getElementById("thresholdSelect").value = "55";
    showMsg(document.getElementById("statusMsg"), "🔄 Reset to 55%", true);
  }
}

// ===== 💧 Sprinkler Duration =====
async function loadDuration() {
  try {
    const res = await fetch("../functions/get_sprinkler_duration.php");
    const val = (await res.text()).trim();
    document.getElementById("durationSelect").value = val || "2000";
  } catch {
    document.getElementById("durationSelect").value = "2000";
  }
}

async function saveDuration() {
  const val = parseInt(document.getElementById("durationSelect").value);
  const res = await fetch("../functions/save_sprinkler_duration.php?duration=" + val);
  if (res.ok) {
    const seconds = (val / 1000).toFixed(1).replace(/\.0$/, "");
    showMsg(document.getElementById("durationStatusMsg"), "✅ Duration saved to " + seconds + " s");
  }
}

async function resetDuration() {
  const res = await fetch("../functions/save_sprinkler_duration.php?duration=2000");
  if (res.ok) {
    document.getElementById("durationSelect").value = "2000";
    showMsg(document.getElementById("durationStatusMsg"), "🔄 Reset to 2 s", true);
  }
}

// ===== 🔄 Initialize on page load =====
document.addEventListener("DOMContentLoaded", () => {
  loadThreshold();
  loadDuration();
  document.getElementById("saveThresholdBtn").addEventListener("click", saveThreshold);
  document.getElementById("resetThresholdBtn").addEventListener("click", resetThreshold);
  document.getElementById("saveDuration").addEventListener("click", saveDuration);
  document.getElementById("resetDurationBtn").addEventListener("click", resetDuration);
});
</script>






<script>
document.addEventListener("DOMContentLoaded", function() {

  // Map relay codes to friendly names
  const relayNames = {
    "RELAY_SHARED": "FAN",
    "RELAY_SOIL": "SPRINKLER"
  };

  // Fan Control
  const fanToggle = document.getElementById("fanToggle");
  fanToggle.addEventListener("change", function() {
    const state = this.checked ? "ON" : "OFF";
    updateRelay("RELAY_SHARED", state);
  });

  // Sprinkler Control
  const sprinklerToggle = document.getElementById("sprinklerToggle");
  sprinklerToggle.addEventListener("change", function() {
    const state = this.checked ? "ON" : "OFF";
    updateRelay("RELAY_SOIL", state);

    // Automatically turn OFF after 1.5 seconds if turned ON
    if (state === "ON") {
      setTimeout(() => {
        sprinklerToggle.checked = false;
        updateRelay("RELAY_SOIL", "OFF");
      }, 5000); 
    }
  });

  // Function to call PHP endpoint
  function updateRelay(relay, state) {
    fetch(`../functions/relay_control.php?relay=${relay}&state=${state}&mode=MANUAL`)
      .then(response => response.text())
      .then(data => {
        console.log(data);
        // Use friendly name if available
        const name = relayNames[relay] || relay.replace("RELAY_", "");
        showNotification(`✅ ${name} turned ${state}`);
      })
      .catch(err => {
        console.error(err);
        showNotification("❌ Connection error");
      });
  }

  // Optional small notification popup
  function showNotification(message) {
    const notif = document.createElement("div");
    notif.textContent = message;
    notif.className = "fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md z-50";
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3000);
  }
});
</script>





<script>
function fetchLastUpdates() {
    fetch('../functions/data_readings.php')
        .then(response => response.json())
        .then(data => {
            // Temperature
            document.getElementById('temp-last').textContent = data.temperature.last;
            document.getElementById('temp-value').textContent = data.temperature.value;

            // Humidity
            document.getElementById('humid-last').textContent = data.humidity.last;
            document.getElementById('humid-value').textContent = data.humidity.value;

            // Moisture
            document.getElementById('moist-last').textContent = data.moisture.last;
            document.getElementById('moist-value').textContent = data.moisture.value;

            // Methane
            document.getElementById('gas-last').textContent = data.methane.last;
            document.getElementById('gas-value').textContent = data.methane.value;
        })
        .catch(error => console.error('Error fetching updates:', error));
}

// Load on page load
fetchLastUpdates();

// Auto-refresh every 60 seconds
setInterval(fetchLastUpdates, 60000);
</script>



<script src="../assets/js/chart.js"></script>
<script>
let uptimeSeconds = 0;
let uptimeInterval = null;

// Helper to format uptime into h m s
function formatUptime(seconds) {
  const h = Math.floor(seconds / 3600);
  const m = Math.floor((seconds % 3600) / 60);
  const s = seconds % 60;

  if (h > 0) return `${h}h ${m}m ${s}s`;
  if (m > 0) return `${m}m ${s}s`;
  return `${s}s`;
}

// Start counting uptime
function startUptime() {
  if (uptimeInterval) return; // already running
  uptimeInterval = setInterval(() => {
    uptimeSeconds++;
    document.getElementById("mc-uptime").textContent = formatUptime(uptimeSeconds);
  }, 1000);
}

// Stop and reset uptime
function stopUptime() {
  clearInterval(uptimeInterval);
  uptimeInterval = null;
  uptimeSeconds = 0;
  document.getElementById("mc-uptime").textContent = "0s";
}

// Main function: Fetch ESP32 status
async function fetchESPStatus() {
  try {
    const response = await fetch("../functions/get_esp_status.php");
    const data = await response.json();

    const statusEl = document.getElementById("esp-status");
    const iconEl = document.getElementById("esp-icon");

    // --- Update online/offline state ---
    if (data.status === "online") {
      statusEl.textContent = "Online";
      statusEl.classList.remove("text-red-600");
      statusEl.classList.add("text-green-600");

      iconEl.classList.remove("bx-no-signal", "text-red-600");
      iconEl.classList.add("bx-signal-5", "text-green-600");

      // ✅ Start uptime when online
      startUptime();

    } else if (data.status === "offline") {
      statusEl.textContent = "Offline";
      statusEl.classList.remove("text-green-600");
      statusEl.classList.add("text-red-600");

      iconEl.classList.remove("bx-signal-5", "text-green-600");
      iconEl.classList.add("bx-no-signal", "text-red-600");

      // ⛔ Stop uptime when offline
      stopUptime();

    } else {
      statusEl.textContent = "Unknown";
      statusEl.classList.remove("text-green-600", "text-red-600");
      statusEl.classList.add("text-gray-600");
      stopUptime();
    }

   const syncEl = document.getElementById("mc-last-sync");
if (data.last_seen) {
  const date = new Date(data.last_seen.replace(" ", "T"));

  // Format date (e.g., Nov 13, 2025)
  const formattedDate = date.toLocaleDateString("en-PH", {
    timeZone: "Asia/Manila",
    year: "numeric",
    month: "short",
    day: "2-digit"
  });

  // Format time (e.g., 12:30 PM)
  const formattedTime = date.toLocaleTimeString("en-PH", {
    timeZone: "Asia/Manila",
    hour12: true,
    hour: "2-digit",
    minute: "2-digit"
  });

  // Combine both
  syncEl.textContent = `${formattedDate} — ${formattedTime}`;
} else {
  syncEl.textContent = "--:--";
}

  } catch (error) {
    console.error("Error fetching ESP32 status:", error);
    stopUptime();
  }
}

// Run immediately, then refresh every 10 seconds
fetchESPStatus();
setInterval(fetchESPStatus, 20000);
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

    // Function to update overview values
    async function updateOverviewValues() {
        try {
            const response = await fetch('../functions/get_sensor_data.php');
            const data = await response.json();

            // Update the values
            document.getElementById('avg-moisture').textContent = Math.round(data.moisture || 0);
            document.getElementById('avg-temp').textContent = Math.round(data.temperature || 0);
            document.getElementById('avg-humidity').textContent = Math.round(data.humidity || 0);
            document.getElementById('water-usage').textContent = Math.round(data.waterUsage || 0);
        } catch (error) {
            console.error('Error updating overview values:', error);
        }
    }

    // Update values initially and every 30 seconds
    updateOverviewValues();
    setInterval(updateOverviewValues, 30000);

    // Reset Settings Modal Logic
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.getElementById('resetSettingsBtn');
        const resetModal = document.getElementById('reset-modal');
        const confirmResetBtn = document.getElementById('confirm-reset');
        const cancelResetBtn = document.getElementById('cancel-reset');

        // Open modal when reset button is clicked
        resetBtn.addEventListener('click', function() {
            resetModal.classList.remove('hidden');
        });

        // Close modal when cancel is clicked
        cancelResetBtn.addEventListener('click', function() {
            resetModal.classList.add('hidden');
        });

        // Handle reset confirmation
        confirmResetBtn.addEventListener('click', async function() {
            try {
                // Reset all inputs to their default values
                document.querySelectorAll('input[type="number"]').forEach(input => {
                    input.value = input.placeholder || '';
                });
                
                document.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });

                document.querySelectorAll('input[type="text"]').forEach(input => {
                    input.value = '';
                });

               
            } catch (error) {
                console.error('Error resetting settings:', error);
                alert('Failed to reset settings. Please try again.');
            }
        });

        // Close modal if clicking outside
        resetModal.addEventListener('click', function(e) {
            if (e.target === resetModal) {
                resetModal.classList.add('hidden');
            }
        });

        // Close modal on Escape key press
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !resetModal.classList.contains('hidden')) {
                resetModal.classList.add('hidden');
            }
        });
    });
</script>


<?php } else {
    $errorM = "Login First!";
    header("Location: ../public/login.php?error=$errorM");

} ?>

