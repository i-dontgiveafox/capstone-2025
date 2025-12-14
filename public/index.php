<?php 
session_start();
if (isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
    // Initialize variables to prevent PHP errors on first load
    $temp_last = '--'; $gas_last = '--'; $moist_last = '--'; $water_last = '--';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
    <link rel="stylesheet" href="../index.css">
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 bg-fixed overflow-x-hidden">
    
    <?php include '../includes/navBar.php'; ?>

    <main class="flex-grow pt-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-6">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-light mb-6">Welcome, <span class="italic font-bold"><?php echo $_SESSION['first_name']; ?></span>!</h1>

            <div class="bg-[url('../assets/img/polygon-bg.jpg')] bg-cover bg-center border-solid rounded-3xl p-6 sm:p-8 shadow-lg flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-3xl font-semibold text-gray-800">Dashboard Monitor</h2>
                    <p class="text-gray-600 mt-1">REAL-TIME SENSOR OVERVIEW</p>
                </div>
                
                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 flex items-center gap-6 shadow-sm border border-white/40">
                    <div class="text-center">
                        <div class="text-xs text-gray-600 uppercase font-bold tracking-wider">Status</div>
                        <div class="flex items-center gap-1 justify-center mt-1">
                            <i id="esp-icon" class='bx bx-help-circle text-gray-400'></i>
                            <span id="esp-status" class="font-bold text-gray-500">Loading...</span>
                        </div>
                    </div>
                    <div class="w-px h-10 bg-gray-400/50"></div>
                    <div class="text-center">
                        <div class="text-xs text-gray-600 uppercase font-bold tracking-wider">Last Sync</div>
                        <div id="mc-last-sync" class="font-medium text-gray-800 mt-1">--:--</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 mb-10">
    
    <div class="grid grid-cols-2 md:grid-cols-2 gap-3 sm:gap-6 mb-3 sm:mb-5">

        <div class="rounded-2xl p-4 sm:p-6 relative shadow-sm bg-[#E2F2EF] h-32 sm:h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">
            <div class="flex flex-row items-center gap-3 sm:gap-4">
                <div class="bg-white rounded-full h-8 w-8 sm:h-12 sm:w-12 flex items-center justify-center shadow-sm shrink-0">
                    <i class='bx bxs-thermometer text-orange-500 text-lg sm:text-2xl'></i>
                </div>
                <div class="min-w-0">
                    <h5 class="text-xs sm:text-lg font-bold text-gray-800 leading-tight truncate">Temperature</h5>
                    <span class="text-[10px] sm:text-xs text-gray-500 font-medium block truncate"><span id="temp-last">--</span></span>
                </div>
            </div>
            
            <div class="text-right self-end">
                <span id="temp-value" class="text-3xl sm:text-5xl font-bold text-gray-800">--</span> 
                <span class="text-sm sm:text-xl text-gray-500 font-medium ml-1">°C</span>
            </div>
        </div>

        <div class="rounded-2xl p-4 sm:p-6 relative shadow-sm bg-[#E2F2EF] h-32 sm:h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">
            <div class="flex flex-row items-center gap-3 sm:gap-4">
                <div class="bg-white rounded-full h-8 w-8 sm:h-12 sm:w-12 flex items-center justify-center shadow-sm shrink-0">
                    <i class='bx bx-water text-blue-500 text-lg sm:text-2xl'></i>
                </div>
                <div class="min-w-0">
                    <h5 class="text-xs sm:text-lg font-bold text-gray-800 leading-tight truncate">Humidity</h5>
                    <span class="text-[10px] sm:text-xs text-gray-500 font-medium block truncate"><span id="humid-last">--</span></span>
                </div>
            </div>
            
            <div class="text-right self-end">
                <span id="humid-value" class="text-3xl sm:text-5xl font-bold text-gray-800">--</span> 
                <span class="text-sm sm:text-xl text-gray-500 font-medium ml-1">%</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-2 gap-3 sm:gap-6">

        <div class="rounded-2xl p-4 sm:p-6 relative shadow-sm bg-[#E2F2EF] h-32 sm:h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">
            <div class="flex flex-row items-center gap-3 sm:gap-4">
                <div class="bg-white rounded-full h-8 w-8 sm:h-12 sm:w-12 flex items-center justify-center shadow-sm shrink-0">
                    <i class='bx bx-droplet text-green-600 text-lg sm:text-2xl'></i>
                </div>
                <div class="min-w-0">
                    <h5 class="text-xs sm:text-lg font-bold text-gray-800 leading-tight truncate">Moisture</h5>
                    <span class="text-[10px] sm:text-xs text-gray-500 font-medium block truncate"><span id="moist-last">--</span></span>
                </div>
            </div>
            
            <div class="text-right self-end">
                <span id="moist-value" class="text-3xl sm:text-5xl font-bold text-gray-800">--</span> 
                <span class="text-sm sm:text-xl text-gray-500 font-medium ml-1">%</span>
            </div>
        </div>

        <div class="rounded-2xl p-4 sm:p-6 relative shadow-sm bg-[#E2F2EF] h-32 sm:h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">
            <div class="flex flex-row items-center gap-3 sm:gap-4">
                <div class="bg-white rounded-full h-8 w-8 sm:h-12 sm:w-12 flex items-center justify-center shadow-sm shrink-0">
                    <i class='bx bx-wind text-gray-500 text-lg sm:text-2xl'></i>
                </div>
                <div class="min-w-0">
                    <h5 class="text-xs sm:text-lg font-bold text-gray-800 leading-tight truncate">Ammonia</h5>
                    <span class="text-[10px] sm:text-xs text-gray-500 font-medium block truncate"><span id="ammonia-last">--</span></span>
                </div>
            </div>
            
            <div class="text-right self-end">
                <span id="ammonia-value" class="text-3xl sm:text-5xl font-bold text-gray-800">--</span> 
                <span class="text-sm sm:text-xl text-gray-500 font-medium ml-1">ppm</span>
            </div>
        </div>

    </div>
</div>
    </main>
    
   <section class="container mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="bg-[url('../assets/img/polygon-bg.jpg')] bg-cover bg-center rounded-3xl p-6 sm:p-8 mb-8 shadow-lg flex flex-col sm:flex-row justify-between items-center gap-4 border border-white/20">
        <div>
            <h2 id="overview-title" class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Daily Overview
            </h2>
            <p class="text-gray-600 font-medium text-sm mt-1 uppercase tracking-wider opacity-80">Sensor History Analysis</p>
        </div>
        
        <!-- <button id="toggle-btn" onclick="toggleOverviewMode()" 
                class="flex items-center gap-2 bg-white/40 hover:bg-[#008f45] text-black/80 hover:text-white px-6 py-3 rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 font-bold text-sm uppercase tracking-wide">
            <i class='bx bx-history text-xl'></i> 
            <span>Check Last 20</span> 
        </button> -->
    </div>
    
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
        
        <div class="relative bg-[url('../assets/images/overview-bg-green.jpg')] bg-cover bg-center rounded-2xl p-4 sm:p-6 shadow-lg h-32 sm:h-36 flex flex-col overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>
            
            <div class="flex flex-col justify-between h-full relative z-10">
                <div>
                    <h3 class="text-sm sm:text-lg font-bold text-white flex items-center gap-1 sm:gap-2 tracking-wide leading-tight">
                        <i class='bx bxs-droplet text-lg'></i> <span class="truncate">Avg. Soil Moisture</span>
                    </h3>
                    <p class="text-xs text-white/90 font-medium mt-1 ml-0.5" id="date-moisture">--</p>
                </div>
                
                <div class="flex items-baseline space-x-1 sm:space-x-2 self-end">
                    <span class="text-3xl sm:text-4xl font-bold text-white drop-shadow-md" id="avg-moisture">--</span> 
                    <span class="text-lg sm:text-xl text-white/80">%</span>
                </div>
            </div>
        </div>

        <div class="relative bg-[url('../assets/images/overview-bg-yellow.png')] bg-cover bg-center rounded-2xl p-4 sm:p-6 shadow-lg h-32 sm:h-36 flex flex-col overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>
            
            <div class="flex flex-col justify-between h-full relative z-10">
                <div>
                    <h3 class="text-sm sm:text-lg font-bold text-white flex items-center gap-1 sm:gap-2 tracking-wide leading-tight">
                        <i class='bx bxs-thermometer text-lg'></i> <span class="truncate">Avg. Temperature</span>
                    </h3>
                    <p class="text-xs text-white/90 font-medium mt-1 ml-0.5" id="date-temp">--</p>
                </div>
                
                <div class="flex items-baseline space-x-1 sm:space-x-2 self-end">
                    <span class="text-3xl sm:text-4xl font-bold text-white drop-shadow-md" id="avg-temp">--</span> 
                    <span class="text-lg sm:text-xl text-white/80">°C</span>
                </div>
            </div>
        </div>

        <div class="relative bg-[url('../assets/images/overview-bg-orange.png')] bg-cover bg-center rounded-2xl p-4 sm:p-6 shadow-lg h-32 sm:h-36 flex flex-col overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>
            
            <div class="flex flex-col justify-between h-full relative z-10">
                <div>
                    <h3 class="text-sm sm:text-lg font-bold text-white flex items-center gap-1 sm:gap-2 tracking-wide leading-tight">
                        <i class='bx bx-water text-lg'></i> <span class="truncate">Avg. Humidity</span>
                    </h3>
                    <p class="text-xs text-white/90 font-medium mt-1 ml-0.5" id="date-humid">--</p>
                </div>
                
                <div class="flex items-baseline space-x-1 sm:space-x-2 self-end">
                    <span class="text-3xl sm:text-4xl font-bold text-white drop-shadow-md" id="avg-humid">--</span> 
                    <span class="text-lg sm:text-xl text-white/80">%</span>
                </div>
            </div>
        </div>

        <div class="relative bg-[url('../assets/images/overview-bg-blue.png')] bg-cover bg-center rounded-2xl p-4 sm:p-6 shadow-lg h-32 sm:h-36 flex flex-col overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>
            
            <div class="flex flex-col justify-between h-full relative z-10">
                <div>
                    <h3 class="text-sm sm:text-lg font-bold text-white flex items-center gap-1 sm:gap-2 tracking-wide leading-tight">
                        <i class='bx bxs-flask text-lg'></i> <span class="truncate">Avg. Ammonia</span>
                    </h3>
                    <p class="text-xs text-white/90 font-medium mt-1 ml-0.5" id="date-ammonia">--</p>
                </div>
                
                <div class="flex items-baseline space-x-1 sm:space-x-2 self-end">
                    <span class="text-3xl sm:text-4xl font-bold text-white drop-shadow-md" id="avg-ammonia">--</span> 
                    <span class="text-lg sm:text-xl text-white/80">%</span>
                </div>
            </div>
        </div>
    </div>

    <div id="charts-section" class="grid grid-cols-1 gap-6 mb-10">
        <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Moisture Trend</h3>
            <div class="w-full h-80 sm:h-96 md:h-96 lg:h-[32rem] bg-gray-100 rounded-2xl flex items-center justify-center">
                <canvas id="moistureChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 gap-6 mb-10">
        <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Temperature and Humidity Trend</h3>
            <div class="w-full h-80 sm:h-96 md:h-96 lg:h-[32rem] bg-gray-100 rounded-2xl flex items-center justify-center">
                <canvas id="tempHumChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 gap-6 mb-10">
        <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ammonia Trend</h3>
            <div class="w-full h-80 sm:h-96 md:h-96 lg:h-[32rem] bg-gray-100 rounded-2xl flex items-center justify-center">
                <canvas id="gasAmmoniaChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>
</section>

    <section class="container mx-auto px-4 sm:px-6 lg:px-8 mt-0 mb-10">
        <div class="bg-[url('../assets/img/polygon-bg.jpg')] bg-cover bg-center rounded-3xl p-6 sm:p-8 mb-8 shadow-lg flex flex-col sm:flex-row justify-between items-center gap-4 border border-white/20">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Control Panel
        </h2>
        <p class="text-gray-600 font-medium text-sm mt-1 uppercase tracking-wider opacity-80">
            Manual Device Operations
        </p>
    </div>
</div>
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl bg-[#E2F2EF]"></div>
                <div class="relative z-10 rounded p-4 flex items-center justify-between text-gray-800">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="flex-shrink-0"><img src="../assets/icons/fan.png" alt="Fan Icon" class="rounded-full p-2 bg-white w-10 h-10 object-contain" /></div>
                        <h3 class="text-lg font-semibold truncate">Fan</h3>
                    </div>
                    <label class="switch relative inline-block w-14 h-8 flex-shrink-0 ml-4">
                        <input type="checkbox" id="fanToggle" class="peer hidden">
                        <span class="slider absolute inset-0 bg-gray-400 rounded-full transition peer-checked:bg-green-500"></span>
                        <span class="dot absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition peer-checked:translate-x-6"></span>
                    </label>
                </div>
            </div>
           <div class="rounded-xl p-4 relative shadow border border-white/20">
    <div class="absolute inset-0 rounded-xl bg-[#E2F2EF]"></div>
    
    <div class="relative z-10 rounded p-4 flex items-center justify-between text-gray-800">
        <div class="flex items-center gap-4 min-w-0">
            <div class="flex-shrink-0">
                <img src="../assets/icons/sprinkler.png" alt="Sprinkler Icon" class="rounded-full p-2 bg-white w-10 h-10 object-contain" />
            </div>
            <h3 class="text-lg font-semibold truncate">Sprinkler</h3>
        </div>

        <button id="runSprinklerBtn" class="bg-white text-gray-700 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-[#B6FC67] hover:text-black hover:shadow-md transition-all duration-200 active:scale-95 text-sm ml-4">
            Run Now
        </button>
    </div>
</div>
        </div>

       <div class="flex flex-col sm:flex-row justify-between items-end sm:items-center mb-6 mt-10 gap-4 px-1">
    
    <div>
        <h2 class="text-2xl font-semibold text-gray-800/90">Set Durations and Thresholds</h2>
        <p class="text-sm text-gray-500 mt-1">Configure sensor triggers and automation timings</p>
    </div>

    <button id="resetSettingsBtn" class="flex items-center gap-2 bg-[#1e1e1e] text-white px-5 py-2.5 rounded-lg shadow-sm hover:bg-[#B6FC67] hover:text-black hover:shadow-md transition-all duration-200 font-medium text-sm w-full sm:w-auto">
        <i class='bx bx-refresh text-lg'></i>
        <span>Reset All Settings</span>
    </button>
</div>

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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-5">
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl bg-[#E2F2EF]"></div>
                <div class="relative z-10 p-4 flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Moisture Level</h3>
                    <div class="flex-grow">
                        <label class="block text-sm text-gray-600 mb-2">Trigger Threshold (%)</label>
                        <div class="relative w-full">
                            <select id="thresholdSelect" class="w-full p-2 pr-10 border rounded-lg bg-white/80 appearance-none">
                                <option value="55">55%</option>
                                <option value="60">60%</option>
                                <option value="65">65%</option>
                                <option value="70">70%</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" /></svg></div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 mt-4">
                        <button id="saveThresholdBtn" class="bg-[#1e1e1e] text-white px-4 py-2 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full">Save Threshold</button>
                        <button id="resetThresholdBtn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition w-full">Reset to Default</button>
                    </div>
                    <div class="h-6 mt-3 flex items-center justify-center"><p id="statusMsg" class="text-sm text-center whitespace-nowrap"></p></div>
                </div>
            </div>
    
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl bg-[#E2F2EF]"></div>
                <div class="relative z-10 p-4 flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ammonia Threshold </h3>
                    <div class="flex-grow">
                        <label class="block text-sm text-gray-600 mb-2">Trigger Threshold (%)</label>
                        <div class="relative w-full">
                            <select id="co2ThresholdSelect" class="w-full p-2 pr-10 border rounded-lg bg-white/80 appearance-none">
                                <option value="0.05">0.05%</option>
                                <option value="0.1">0.10%</option>
                                <option value="0.15">0.15%</option>
                                <option value="0.2">0.20%</option>
                                <option value="0.05" selected>0.05%</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" /></svg></div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 mt-4">
                        <button id="saveCo2ThresholdBtn" class="bg-[#1e1e1e] text-white px-4 py-2 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full">Save Threshold</button>
                        <button id="resetCo2ThresholdBtn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition w-full">Reset to Default</button>
                    </div>
                    <div class="h-6 mt-3 flex items-center justify-center"><p id="co2StatusMsg" class="text-sm text-center whitespace-nowrap"></p></div>
                </div>
            </div>
    
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl bg-[#E2F2EF]"></div>
                <div class="relative z-10 p-4 flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Temperature Threshold </h3>
                    <div class="flex-grow">
                        <label class="block text-sm text-gray-600 mb-2">Trigger Threshold (%)</label>
                        <div class="relative w-full">
                            <select id="tempThresholdSelect" class="w-full p-2 pr-10 border rounded-lg bg-white/80 appearance-none">
                                <option value="25">25%</option>
                                <option value="28">28%</option>
                                <option value="29">29%</option>
                                <option value="30">30%</option>
                                <option value="31" selected>31%</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" /></svg></div>
                            </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 mt-4">
                        <button id="saveTempThresholdBtn" class="bg-[#1e1e1e] text-white px-4 py-2 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full">Save Threshold</button>
                        <button id="resetTempThresholdBtn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition w-full">Reset to Default</button>
                    </div>
                    <div class="h-6 mt-3 flex items-center justify-center"><p id="tempStatusMsg" class="text-sm text-center whitespace-nowrap"></p></div>
                </div>
            </div>
    
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl bg-[#E2F2EF]"></div>
                <div class="relative z-10 p-4 flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sprinkler Duration</h3>
                    <div class="flex-grow">
                        <label class="block text-sm text-gray-600 mb-2">Set Duration</label>
                        <div class="relative w-full">
                            <select id="durationSelect" class="w-full p-2 pr-10 border rounded-lg bg-white/80 appearance-none">
                                <option value="2000">2 seconds</option>
                                <option value="3000">3 seconds</option>
                                <option value="5000" selected>5 seconds</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" /></svg></div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 mt-4">
                        <button id="saveDuration" class="bg-[#1e1e1e] text-white px-4 py-2 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full">Save Duration</button>
                        <button id="resetDurationBtn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition w-full">Reset to Default</button>
                    </div>
                    <div class="h-6 mt-3 flex items-center justify-center"><p id="durationStatusMsg" class="text-sm text-center whitespace-nowrap"></p></div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/chart.js"></script>

<script>
// Track the current mode: 'today' or 'last20'
let currentMode = 'today'; 

// Function to handle the button click
function toggleOverviewMode() {
    // 1. Flip the mode
    currentMode = (currentMode === 'today') ? 'last20' : 'today';
    
    // 2. Update the Button Text & Title
    const btnText = document.querySelector('#toggle-btn span');
    const btnIcon = document.querySelector('#toggle-btn i');
    const title = document.getElementById('overview-title');

    if (currentMode === 'last20') {
        title.textContent = "Last 20 Readings";
        btnText.textContent = "Back to Daily Avg";
        btnIcon.className = 'bx bx-calendar text-lg'; // Change icon
    } else {
        title.textContent = "Daily Overview";
        btnText.textContent = "Check Last 20";
        btnIcon.className = 'bx bx-history text-lg'; // Change icon back
    }

    // 3. Refresh data immediately to show new numbers
    updateDashboard();
}

async function updateDashboard() {
    try {
        const response = await fetch("../functions/get_dashboard_cached.php?t=" + new Date().getTime());
        const data = await response.json();

        // Helper: Format Date
        function formatTime(timestamp) {
            if (!timestamp) return '--';
            let timeString = timestamp;
            if (typeof timeString === 'string' && !timeString.includes('UTC') && !timeString.includes('Z')) {
                timeString += ' UTC';
            }
            const date = new Date(timeString);
            if (isNaN(date.getTime())) return '--';
            return date.toLocaleString('en-US', {
                timeZone: 'Asia/Manila', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true
            });
        }

        // 1. Status Update
        const statusEl = document.getElementById("esp-status");
        const iconEl = document.getElementById("esp-icon");
        let globalLastSync = '--';

        if (data.status) {
            if (data.status.status === "online") {
                statusEl.textContent = "Online";
                statusEl.className = "text-green-600 font-bold";
                iconEl.className = "bx bx-signal-5 text-green-600";
            } else {
                statusEl.textContent = "Offline";
                statusEl.className = "text-red-600 font-bold";
                iconEl.className = "bx bx-no-signal text-red-600";
            }
            if(data.status.last_seen) {
                globalLastSync = formatTime(data.status.last_seen);
                const lastSyncEl = document.getElementById("mc-last-sync");
                if(lastSyncEl) lastSyncEl.textContent = globalLastSync;
            }
        }

        // 2. Sensor Cards
        if (data.sensors) {
            const setTxt = (id, val) => {
                const el = document.getElementById(id);
                if(el) el.textContent = val ?? '--';
            };
            setTxt('temp-value', data.sensors.temperature);
            setTxt('humid-value', data.sensors.humidity);
            setTxt('moist-value', data.sensors.moisture);
            setTxt('gas-value', data.sensors.methane);
            
            const ammoniaVal = data.sensors.ammonia ?? '--';
            const ammoniaEl = document.getElementById('ammonia-value');
            if(ammoniaEl) {
                ammoniaEl.textContent = ammoniaVal;
                if (ammoniaVal !== '--') ammoniaEl.style.color = '#374151';
            }

            const timeLabel = globalLastSync;
            setTxt('temp-last', timeLabel);
            setTxt('humid-last', timeLabel);
            setTxt('moist-last', timeLabel);
            setTxt('gas-last', timeLabel);
            setTxt('ammonia-last', timeLabel);
        }

        // 3. Water Level
        if (data.water) {
            const wVal = document.getElementById('water-value');
            const wLast = document.getElementById('water-last');
            if(wVal) wVal.textContent = data.water.water_value ?? '--';
            if(wLast) wLast.textContent = formatTime(data.water.last_update);
        }

       // 4. AVERAGES UPDATE (With Time Format)
        if (data.averages) {
            const source = data.averages[currentMode] || data.averages.today;
            const format = (val) => Math.round(parseFloat(val) || 0);

            // Calculate the TIME string
            let timeStr = '--';
            if (data.sensors && data.sensors.timestamp) {
                let ts = data.sensors.timestamp;
                if (!ts.includes('Z') && !ts.includes('UTC')) ts += ' UTC';
                
                const dateObj = new Date(ts);
                if (!isNaN(dateObj.getTime())) {
                    // Format: "Dec 14 • 9:45 AM"
                    const datePart = dateObj.toLocaleDateString('en-US', { timeZone: 'Asia/Manila', month: 'short', day: 'numeric' });
                    const timePart = dateObj.toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: 'numeric', minute: '2-digit', hour12: true });
                    timeStr = `${datePart} • ${timePart}`;
                }
            }

            const setAvg = (id, val, dateId) => {
                // Update Value
                const el = document.getElementById(id);
                if(el) el.textContent = format(val);
                
                // Update Time Label
                const dateEl = document.getElementById(dateId);
                if(dateEl) dateEl.textContent = timeStr;
            };

            setAvg('avg-temp', source.avg_temp, 'date-temp');
            setAvg('avg-humid', source.avg_humid, 'date-humid');
            setAvg('avg-moisture', source.avg_moisture, 'date-moisture');
            setAvg('avg-ammonia', source.avg_ammonia, 'date-ammonia');
        }
    } catch (error) {
        console.error("Dashboard update error:", error);
    }
}

// Initial Load
updateDashboard();
// Refresh every 5 seconds
setInterval(updateDashboard, 5000); 
</script>
<script>
const CHART_INTERVAL = 5000;
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    animation: { duration: 0 },
    scales: {
        x: {
            ticks: {
                maxTicksLimit: 6, 
                maxRotation: 45,  
                minRotation: 45,
                font: { size: 10 } 
            },
            grid: { display: false } 
        },
        y: {
            beginAtZero: true,
            ticks: { font: { size: 10 } }
        }
    },
    plugins: {
        legend: { 
            position: 'bottom',
            labels: { boxWidth: 12, padding: 15, font: { size: 11 } }
        }
    }
};

// --- TIME HELPER 1: FOR MOISTURE (Already Correct) ---
// This assumes the data is ALREADY in Philippines Time
function formatLocalTime(timestamp) {
    if (!timestamp) return '--';
    const date = new Date(timestamp);
    if (isNaN(date.getTime())) return timestamp; 

    return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}

// --- TIME HELPER 2: FOR TEMP & AMMONIA (Needs +8 Hours) ---
// This forces the browser to treat the data as UTC, which automatically adds +8 hours for PH
function formatUTCTime(timestamp) {
    if (!timestamp) return '--';

    // Append ' UTC' to force timezone conversion
    let timeString = timestamp;
    if (typeof timeString === 'string' && !timeString.includes('UTC') && !timeString.includes('Z')) {
        timeString += ' UTC';
    }

    const date = new Date(timeString);
    if (isNaN(date.getTime())) return timestamp;

    return date.toLocaleTimeString('en-US', {
        timeZone: 'Asia/Manila', // Forces conversion to Philippines Time
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}

// 1. MOISTURE CHART (Uses Local Time - No Change)
async function loadMoistureChart() {
    try {
        const response = await fetch("../functions/get_moisture_data.php");
        const data = await response.json();
        if (!Array.isArray(data) || data.length === 0) return;

        // ✅ USES LOCAL TIME FORMATTER
        const labels = data.map(item => formatLocalTime(item.timestamp));
        const moistureValues = data.map(item => item.moisture);
        const ctx = document.getElementById("moistureChart").getContext("2d");

        if (window.moistureChartInstance) window.moistureChartInstance.destroy();

        window.moistureChartInstance = new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Soil Moisture (%)",
                    data: moistureValues,
                    borderColor: "#16a34a",
                    backgroundColor: "rgba(22,163,74,0.2)",
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 2 
                }]
            },
            options: {
                ...commonOptions, 
                scales: {
                    ...commonOptions.scales,
                    y: { ...commonOptions.scales.y, max: 100 } 
                }
            }
        });
    } catch (e) { console.error(e); }
}

// 2. TEMP & HUM CHART (Uses UTC Formatter to Fix Offset)
async function loadTempHumChart() {
    try {
        const response = await fetch("../functions/get_temp_hum_data.php");
        const data = await response.json();
        if (!Array.isArray(data) || data.length === 0) return;

        // ✅ USES UTC FORMATTER (Adds +8 Hours)
        const labels = data.map(item => formatUTCTime(item.timestamp));
        const tempValues = data.map(item => item.temperature);
        const humValues = data.map(item => item.humidity);
        const ctx = document.getElementById("tempHumChart").getContext("2d");

        if (window.tempHumChartInstance) window.tempHumChartInstance.destroy();

        window.tempHumChartInstance = new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    { label: "Temp (°C)", data: tempValues, borderColor: "#f59e0b", backgroundColor: "rgba(245,158,11,0.2)", fill: true, tension: 0.4, borderWidth: 2, pointRadius: 2 },
                    { label: "Humidity (%)", data: humValues, borderColor: "#3b82f6", backgroundColor: "rgba(59,130,246,0.2)", fill: true, tension: 0.4, borderWidth: 2, pointRadius: 2 }
                ]
            },
            options: commonOptions
        });
    } catch (e) { console.error(e); }
}

// 3. AMMONIA CHART (Uses UTC Formatter to Fix Offset)
async function loadGasAmmoniaChart() {
    try {
        const response = await fetch("../functions/get_gas_ammonia_data.php");
        const result = await response.json();
        
        if (!result.ammonia) return;

        // ✅ USES UTC FORMATTER (Adds +8 Hours)
        const labels = result.ammonia.map(item => formatUTCTime(item.timestamp));
        
        const ammoniaValues = result.ammonia.map(item => item.ammonia);
        
        const ctx = document.getElementById("gasAmmoniaChart").getContext("2d");

        if (window.gasAmmoniaChartInstance) window.gasAmmoniaChartInstance.destroy();

        window.gasAmmoniaChartInstance = new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    { 
                        label: "Ammonia (ppm)", 
                        data: ammoniaValues, 
                        borderColor: "#9333ea", 
                        backgroundColor: "rgba(147,51,234,0.2)", 
                        fill: true, 
                        tension: 0.4, 
                        borderWidth: 2, 
                        pointRadius: 2 
                    }
                ]
            },
            options: commonOptions
        });
    } catch (e) { console.error(e); }
}

document.addEventListener("DOMContentLoaded", () => {
    loadMoistureChart();
    loadTempHumChart();
    loadGasAmmoniaChart();
    setInterval(loadMoistureChart, CHART_INTERVAL);
    setInterval(loadTempHumChart, CHART_INTERVAL);
    setInterval(loadGasAmmoniaChart, CHART_INTERVAL);
});
</script>
<style>
#statusMsg, #durationStatusMsg { transition: all 0.4s ease; opacity: 0; }
.pulse { animation: pulse 0.4s ease; }
@keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.08); } 100% { transform: scale(1); } }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// --- SOIL MOISTURE THRESHOLD FUNCTIONS ---

async function loadThreshold() {
    try {
        const res = await fetch("../functions/get_soil_threshold.php");
        const val = (await res.text()).trim();
        document.getElementById("thresholdSelect").value = val;
    } catch { console.error("Failed to load soil threshold."); }
}

async function saveThreshold() {
    const val = document.getElementById("thresholdSelect").value;
    const btn = document.getElementById("saveThresholdBtn");
    
    // UI Feedback
    const originalText = btn.innerText;
    btn.innerText = "Saving...";

    try {
        const res = await fetch("../functions/update_soil_threshold.php", {
            method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "threshold=" + val
        });
        const data = await res.text();

        // CHECK IF SAVE WAS SUCCESSFUL
        if (res.ok && (data.includes("success") || data.includes("✅") || data.includes("updated"))) {
            // ✅ SHOW THE POPUP LOGO
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Moisture Threshold set to ' + val + '%',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire("Error", "Could not save threshold", "error");
        }
    } catch (err) {
        Swal.fire("Error", "Connection failed", "error");
    } finally {
        btn.innerText = originalText;
    }
}

async function resetThreshold() {
    // ... existing reset logic, but add Swal if you want popup here too ...
    const res = await fetch("../functions/update_soil_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "threshold=55"
    });
    if (res.ok) {
        document.getElementById("thresholdSelect").value = "55";
        Swal.fire({ icon: 'success', title: 'Reset!', text: 'Reset to 55%', timer: 1500, showConfirmButton: false });
    }
}

// --- CO2 THRESHOLD FUNCTIONS ---
// (Your existing CO2 code is fine, assuming it works for you)
document.addEventListener("DOMContentLoaded", () => {
    loadCo2Threshold();
    const saveBtn = document.getElementById("saveCo2ThresholdBtn");
    const resetBtn = document.getElementById("resetCo2ThresholdBtn");
    if (saveBtn) saveBtn.addEventListener("click", saveCo2Threshold);
    if (resetBtn) resetBtn.addEventListener("click", resetCo2Threshold);
});

async function loadCo2Threshold() {
    try {
        const res = await fetch("../functions/get_co2_threshold.php");
        const val = (await res.text()).trim();
        const select = document.getElementById("co2ThresholdSelect");
        if(select) select.value = val; 
    } catch (err) { console.error("Failed to load CO2 threshold:", err); }
}

async function saveCo2Threshold() {
    const val = document.getElementById("co2ThresholdSelect").value;
    const res = await fetch("../functions/update_co2_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "co2_threshold=" + val
    });

    // Use Popup here too for consistency
    if (res.ok) {
        Swal.fire({ icon: 'success', title: 'Saved!', text: 'Ammonia Threshold saved: ' + val + '%', timer: 2000, showConfirmButton: false });
    }
}

async function resetCo2Threshold() {
    const defaultVal = "0.05";
    const res = await fetch("../functions/update_co2_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "co2_threshold=" + defaultVal
    });
    if (res.ok) {
        document.getElementById("co2ThresholdSelect").value = defaultVal;
        Swal.fire({ icon: 'success', title: 'Reset!', text: 'Reset to ' + defaultVal + '%', timer: 1500, showConfirmButton: false });
    }
}


// --- TEMPERATURE THRESHOLD FUNCTIONS ---
async function loadTempThreshold() {
    try {
        const res = await fetch("../functions/get_temp_threshold.php");
        const val = (await res.text()).trim();
        document.getElementById("tempThresholdSelect").value = val;
    } catch { console.error("Failed to load Temperature threshold."); }
}

async function saveTempThreshold() {
    const val = document.getElementById("tempThresholdSelect").value;
    const res = await fetch("../functions/update_temp_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "temp_threshold=" + val
    });
    if (res.ok) {
        Swal.fire({ icon: 'success', title: 'Saved!', text: 'Temp Threshold saved to ' + val + '%', timer: 2000, showConfirmButton: false });
    }
}

async function resetTempThreshold() {
    const res = await fetch("../functions/update_temp_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "temp_threshold=31"
    });
    if (res.ok) {
        document.getElementById("tempThresholdSelect").value = "31";
        Swal.fire({ icon: 'success', title: 'Reset!', text: 'Reset to 31%', timer: 1500, showConfirmButton: false });
    }
}


// --- SPRINKLER DURATION FUNCTIONS (THIS WAS YOUR PROBLEM) ---

async function loadDuration() {
    try {
        const res = await fetch("../functions/get_sprinkler_duration.php");
        const val = (await res.text()).trim();
        document.getElementById("durationSelect").value = val || "2000";
    } catch { document.getElementById("durationSelect").value = "2000"; }
}

async function saveDuration(e) {
    if(e) e.preventDefault(); // Stop page reload
    
    const val = parseInt(document.getElementById("durationSelect").value);
    const btn = document.getElementById("saveDuration");
    
    // UI Feedback
    const originalText = btn.innerText;
    btn.innerText = "Saving...";

    try {
        // Send request
        const res = await fetch("../functions/save_sprinkler_duration.php?duration=" + val);
        const data = await res.text(); // Get the response text from PHP

        // ✅ THIS IS THE FIX: Check for keywords and SHOW POPUP
        if (data.includes("success") || data.includes("✅") || data.includes("updated")) {
            
            const seconds = (val / 1000).toFixed(1).replace(/\.0$/, "");
            
            // Show the Logo Popup
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Duration set to ' + seconds + ' seconds',
                timer: 2000,
                showConfirmButton: false
            });

        } else {
            // Show Error Popup
            Swal.fire("Error", "Failed to save: " + data, "error");
        }
    } catch (err) {
        console.error(err);
        Swal.fire("Error", "Connection Error", "error");
    } finally {
        btn.innerText = originalText;
    }
}

async function resetDuration() {
    const res = await fetch("../functions/save_sprinkler_duration.php?duration=2000");
    if (res.ok) {
        document.getElementById("durationSelect").value = "2000";
        Swal.fire({ icon: 'success', title: 'Reset!', text: 'Reset to 2s', timer: 1500, showConfirmButton: false });
    }
}

// --- EVENT LISTENERS ---
document.addEventListener("DOMContentLoaded", () => {
    loadThreshold();
    loadCo2Threshold();
    loadTempThreshold();
    loadDuration();
    
    // Soil Moisture
    document.getElementById("saveThresholdBtn").addEventListener("click", saveThreshold);
    document.getElementById("resetThresholdBtn").addEventListener("click", resetThreshold);
    
    // Temperature
    document.getElementById("saveTempThresholdBtn").addEventListener("click", saveTempThreshold);
    document.getElementById("resetTempThresholdBtn").addEventListener("click", resetTempThreshold);

    // Sprinkler
    const saveDurBtn = document.getElementById("saveDuration");
    if(saveDurBtn) saveDurBtn.addEventListener("click", saveDuration);
    
    document.getElementById("resetDurationBtn").addEventListener("click", resetDuration);
});
document.addEventListener("DOMContentLoaded", function() {
    
    // Names for notifications
    const relayNames = { "RELAY_SHARED": "FAN", "RELAY_SOIL": "SPRINKLER" };

    // --- 1. FAN CONTROL (Keep as Toggle) ---
    const fanToggle = document.getElementById("fanToggle");
    if(fanToggle) {
        fanToggle.addEventListener("change", function() { 
            updateRelay("RELAY_SHARED", this.checked ? "ON" : "OFF"); 
        });
    }

   // --- SPRINKLER CONTROL (With Confirmation) ---
const runBtn = document.getElementById("runSprinklerBtn");

if (runBtn) {
    runBtn.addEventListener("click", function(e) {
        e.preventDefault();

        // 1. Show Confirmation Popup
        Swal.fire({
            title: 'Run Sprinkler?',
            text: "This will turn on the sprinkler for 2 seconds.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6', // Blue confirm button
            cancelButtonColor: '#d33',    // Red cancel button
            confirmButtonText: 'Yes!'
        }).then((result) => {
            
            // 2. Only proceed if user clicked "Yes"
            if (result.isConfirmed) {
                executeSprinklerRun();
            }
        });
    });

    // Helper Function: The actual logic to run the sprinkler
    function executeSprinklerRun() {
        // A. Visual Feedback
        const originalText = runBtn.innerText;
        runBtn.innerText = "Running...";
        
        // Make button Green and Disabled
        runBtn.classList.remove("bg-white", "text-gray-700");
        runBtn.classList.add("bg-green-500", "text-white", "cursor-not-allowed");
        runBtn.disabled = true;

        // B. Send "ON" Command to Database
        updateRelay("RELAY_SOIL", "ON");

        // C. Wait 2 Seconds, then Turn OFF
        setTimeout(() => {
            // Turn OFF in Database (Stops the loop)
            updateRelay("RELAY_SOIL", "OFF"); 

            // Reset Button Visuals
            runBtn.innerText = originalText;
            runBtn.classList.remove("bg-green-500", "text-white", "cursor-not-allowed");
            runBtn.classList.add("bg-white", "text-gray-700");
            runBtn.disabled = false; 
            
            // Optional: Success message
            Swal.fire({
                title: 'Finished!',
                text: 'Sprinkler cycle complete.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });

        }, 1800); // 2000ms duration
    }
}

    // --- HELPER FUNCTIONS ---
    function updateRelay(relay, state) {
        // We use 'MANUAL' mode so the database knows a user clicked this
        fetch(`../functions/relay_control.php?relay=${relay}&state=${state}&mode=MANUAL`)
            .then(response => response.text())
            .then(data => {
                console.log(relay + " -> " + state);
            })
            .catch(err => console.error(err));
    }
});
// RESET MODAL (UPDATED to include new resets)
const resetBtn = document.getElementById("resetSettingsBtn");
const resetModal = document.getElementById("reset-modal");
const cancelReset = document.getElementById("cancel-reset");
const confirmReset = document.getElementById("confirm-reset");

resetBtn.addEventListener("click", () => resetModal.classList.remove("hidden"));
cancelReset.addEventListener("click", () => resetModal.classList.add("hidden"));
resetModal.addEventListener("click", (e) => { if (e.target === resetModal) resetModal.classList.add("hidden"); });

confirmReset.addEventListener("click", async () => {
    confirmReset.disabled = true;
    confirmReset.textContent = "Resetting...";
    try {
        // Resetting all thresholds and duration to default values
        await Promise.all([
            fetch("../functions/update_soil_threshold.php", { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "threshold=55" }),
            fetch("../functions/update_co2_threshold.php", { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "co2_threshold=0.05" }), // NEW RESET
            fetch("../functions/update_temp_threshold.php", { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "temp_threshold=31" }), // NEW RESET
            fetch("../functions/save_sprinkler_duration.php?duration=2000")
        ]);

        // Update UI selections
        document.getElementById("thresholdSelect").value = "55";
        document.getElementById("co2ThresholdSelect").value = "0.05"; // NEW UI UPDATE
        document.getElementById("tempThresholdSelect").value = "31"; // NEW UI UPDATE
        document.getElementById("durationSelect").value = "2000";
        
        showToast("✅ Settings have been reset to default values!", false);
    } catch {
        showToast("❌ Failed to reset settings. Please try again.", true);
    } finally {
        confirmReset.disabled = false;
        confirmReset.textContent = "Reset Settings";
        resetModal.classList.add("hidden");
    }
});

function showToast(message, isError = false) {
    const toast = document.createElement("div");
    toast.textContent = message;
    toast.className = `fixed bottom-6 right-6 px-4 py-2 rounded-lg shadow-lg text-white z-50 transform translate-y-4 opacity-0 transition-all duration-300 ease-out ${isError ? "bg-red-600" : "bg-green-600"}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.classList.remove("translate-y-4", "opacity-0"); toast.classList.add("translate-y-0", "opacity-100"); }, 50);
    setTimeout(() => { toast.classList.remove("opacity-100"); toast.classList.add("opacity-0", "translate-y-2"); setTimeout(() => toast.remove(), 400); }, 3000);
}
</script>

<?php } else {
    $errorM = "Login First!";
    header("Location: ../public/login.php?error=$errorM");
} ?>