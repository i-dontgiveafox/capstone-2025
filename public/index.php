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
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">

                <div class="rounded-2xl p-6 relative shadow-sm bg-[#E2F2EF] h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">
                    <div class="flex items-start gap-4">
                        <div class="bg-white rounded-full h-12 w-12 flex items-center justify-center shadow-sm shrink-0">
                            <i class='bx bxs-thermometer text-orange-500 text-2xl'></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-bold text-gray-800 leading-tight">Temperature</h5>
                            <span class="text-xs text-gray-500 font-medium">Updated: <span id="temp-last">--</span></span>
                        </div>
                    </div>
                    <div class="text-right self-end">
                        <span id="temp-value" class="text-5xl font-bold text-gray-800">--</span> 
                        <span class="text-xl text-gray-500 font-medium ml-1">°C</span>
                    </div>
                </div>

                <div class="rounded-2xl p-6 relative shadow-sm bg-[#E2F2EF] h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">
                    <div class="flex items-start gap-4">
                        <div class="bg-white rounded-full h-12 w-12 flex items-center justify-center shadow-sm shrink-0">
                            <i class='bx bx-water text-blue-500 text-2xl'></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-bold text-gray-800 leading-tight">Humidity</h5>
                            <span class="text-xs text-gray-500 font-medium">Updated: <span id="humid-last">--</span></span>
                        </div>
                    </div>
                    <div class="text-right self-end">
                        <span id="humid-value" class="text-5xl font-bold text-gray-800">--</span> 
                        <span class="text-xl text-gray-500 font-medium ml-1">%</span>
                    </div>
                </div>

                

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!--<div class="rounded-2xl p-6 relative shadow-sm bg-[#E2F2EF] h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">-->
                <!--    <div class="flex items-start gap-4">-->
                <!--        <div class="bg-white rounded-full h-12 w-12 flex items-center justify-center shadow-sm shrink-0">-->
                <!--            <i class='bx bxs-flask text-purple-500 text-2xl'></i>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <h5 class="text-lg font-bold text-gray-800 leading-tight"></h5>-->
                <!--            <span class="text-xs text-gray-500 font-medium">Updated: <span id="ammonia-last">--</span></span>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="text-right self-end">-->
                <!--        <span id="ammonia-value" class="text-5xl font-bold text-gray-800">--</span> -->
                <!--        <span class="text-xl text-gray-500 font-medium ml-1">ppm</span>-->
                <!--    </div>-->
                <!--</div>-->
                
                
                <div class="rounded-2xl p-6 relative shadow-sm bg-[#E2F2EF] h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">
                    <div class="flex items-start gap-4">
                        <div class="bg-white rounded-full h-12 w-12 flex items-center justify-center shadow-sm shrink-0">
                            <i class='bx bx-droplet text-green-600 text-2xl'></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-bold text-gray-800 leading-tight">Soil Moisture</h5>
                            <span class="text-xs text-gray-500 font-medium">Updated: <span id="moist-last">--</span></span>
                        </div>
                    </div>
                    <div class="text-right self-end">
                        <span id="moist-value" class="text-5xl font-bold text-gray-800">--</span> 
                        <span class="text-xl text-gray-500 font-medium ml-1">%</span>
                    </div>
                </div>
                
                

                <div class="rounded-2xl p-6 relative shadow-sm bg-[#E2F2EF] h-44 flex flex-col justify-between transition hover:-translate-y-1 duration-300">
                    <div class="flex items-start gap-4">
                        <div class="bg-white rounded-full h-12 w-12 flex items-center justify-center shadow-sm shrink-0">
                            <i class='bx bx-wind text-gray-500 text-2xl'></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-bold text-gray-800 leading-tight">Ammonia (NH₃)</h5>
                            <span class="text-xs text-gray-500 font-medium">Updated: <span id="ammonia-last">--</span></span>
                        </div>
                    </div>
                    <div class="text-right self-end">
                        <span id="ammonia-value" class="text-5xl font-bold text-gray-800">--</span> 
                        <span class="text-xl text-gray-500 font-medium ml-1">%</span>
                    </div>
                </div>

                </div>
        </div>
    </main>
    
    <section class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800/90 mb-4 mt-4 px-4">Daily Overview</h2>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="relative bg-[url('../assets/images/overview-bg-green.jpg')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
                <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>
                <div class="flex flex-col space-y-2 relative z-10">
                    <h3 class="text-lg font-semibold text-white"><i class='bx bxs-droplet'></i> Avg. Soil Moisture</h3>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-bold text-white" id="avg-moisture">--</span> <span class="text-xl text-white/80">%</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-[url('../assets/images/overview-bg-yellow.png')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
                <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>
                <div class="flex flex-col space-y-2 relative z-10">
                    <h3 class="text-lg font-semibold text-white"><i class='bx bxs-droplet'></i> Avg. Temperature</h3>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-bold text-white" id="avg-temp">--</span> <span class="text-xl text-white/80">°C</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-[url('../assets/images/overview-bg-orange.png')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
                <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent pointer-events-none"></div>
                <div class="flex flex-col space-y-2 relative z-10">
                    <h3 class="text-lg font-semibold text-white"><i class='bx bxs-droplet'></i> Avg. Humidity</h3>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-bold text-white" id="avg-humid">--</span> <span class="text-xl text-white/80">%</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-[url('../assets/images/overview-bg-blue.png')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
              <div class="flex flex-col space-y-2 relative z-10">
                <h3 class="text-lg font-semibold text-white"><i class='bx bxs-flask'></i> Avg. CO₂ Gas</h3>
                <div class="flex items-baseline space-x-2">
                  <span class="text-4xl font-bold text-white" id="avg-co2">--</span> <span class="text-xl text-white/80">ppm</span>
                </div>
              </div>
            </div>
            <div class="relative bg-[url('../assets/images/overview-bg-yellow.png')] bg-cover bg-center rounded-3xl p-6 mb-6 shadow-lg overflow-hidden">
              <div class="flex flex-col space-y-2 relative z-10">
                <h3 class="text-lg font-semibold text-white"><i class='bx bxs-flask'></i> Avg. Ammonia</h3>
                <div class="flex items-baseline space-x-2">
                  <span class="text-4xl font-bold text-white" id="avg-ammonia">--</span> <span class="text-xl text-white/80">ppm</span>
                </div>
              </div>
            </div>
        </div>

        <div id="charts-section" class="grid grid-cols-1 gap-6 mb-10">
            <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Moisture Trend</h3>
                <div class="w-full h-70 sm:h-80 md:h-96 lg:h-[32rem] bg-gray-100 rounded-2xl flex items-center justify-center">
                    <canvas id="moistureChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-6 mb-10">
            <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Temperature and Humidity Trend</h3>
                <div class="w-full h-70 sm:h-80 md:h-96 lg:h-[32rem] bg-gray-100 rounded-2xl flex items-center justify-center">
                    <canvas id="tempHumChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-6 mb-10">
            <div class="bg-white/90 rounded-3xl shadow-lg p-6 flex flex-col justify-center items-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Gas and Ammonia Trend</h3>
                <div class="w-full h-70 sm:h-80 md:h-96 lg:h-[32rem] bg-gray-100 rounded-2xl flex items-center justify-center">
                    <canvas id="gasAmmoniaChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-10">
        <div class="bg-[url('../assets/img/polygon-bg.jpg')] bg-cover bg-center rounded-3xl p-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-2xl font-semibold text-gray-800">Control Panel</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
            <div class="rounded-xl p-4 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px);"></div>
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
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px);"></div>
                <div class="relative z-10 rounded p-4 flex items-center justify-between text-gray-800">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="flex-shrink-0"><img src="../assets/icons/fan.png" alt="Sprinkler Icon" class="rounded-full p-2 bg-white w-10 h-10 object-contain" /></div>
                        <h3 class="text-lg font-semibold truncate">Sprinkler</h3>
                    </div>
                    <label class="switch relative inline-block w-14 h-8 flex-shrink-0 ml-4">
                        <input type="checkbox" id="sprinklerToggle" class="peer hidden">
                        <span class="slider absolute inset-0 bg-gray-400 rounded-full transition peer-checked:bg-green-500"></span>
                        <span class="dot absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition peer-checked:translate-x-6"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-start justify-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800/90 px-4 mb-4 mt-10">Set Durations and Thresholds</h2>
            <button id="resetSettingsBtn" class="bg-[#1e1e1e] text-white px-2 pr-4 rounded-lg hover:bg-[#B6FC67] hover:text-black transition w-full sm:w-auto sm:ml-auto">
                <i class='bx bx-refresh px-2 py-3'></i>Reset
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
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px);"></div>
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
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px);"></div>
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
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px);"></div>
                <div class="relative z-10 p-4 flex flex-col h-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Temperature Threshold </h3>
                    <div class="flex-grow">
                        <label class="block text-sm text-gray-600 mb-2">Trigger Threshold (%)</label>
                        <div class="relative w-full">
                            <select id="tempThresholdSelect" class="w-full p-2 pr-10 border rounded-lg bg-white/80 appearance-none">
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
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px);"></div>
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
async function updateDashboard() {
    try {
        // Adding ?nocache=... forces the browser to get a fresh version every time
        const response = await fetch("../functions/get_dashboard_cached.php");
        const data = await response.json();

        // --- HELPER: Format Date & Time to Manila Time ---
        const formatTime = (dateStr) => {
            if (!dateStr || dateStr === '--') return '--';
            const date = new Date(dateStr.replace(" ", "T") + "Z");
            return date.toLocaleString('en-US', { 
                timeZone: 'Asia/Manila', 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: 'numeric', 
                minute: '2-digit', 
                hour12: true 
            });
        };

        // 1. Update ESP Status & Last Sync
        const statusEl = document.getElementById("esp-status");
        const iconEl = document.getElementById("esp-icon");
        
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
                document.getElementById("mc-last-sync").textContent = formatTime(data.status.last_seen);
            }
        }

        // 2. Update Sensor Values & Timestamps
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
                if (ammoniaVal !== '--') {
                    const val = parseFloat(ammoniaVal);
                    if (val <= 8) ammoniaEl.style.color = '#374151'; 
                    else if (val <= 15) ammoniaEl.style.color = '#374151'; 
                    else ammoniaEl.style.color = '#374151'; 
                }
            }

            const time = formatTime(data.sensors.timestamp);
            setTxt('temp-last', time);
            setTxt('humid-last', time);
            setTxt('moist-last', time);
            setTxt('gas-last', time);
            setTxt('ammonia-last', time);
        }

        // 3. Update Water Level
        if (data.water) {
            document.getElementById('water-value').textContent = data.water.water_value ?? '--';
            document.getElementById('water-last').textContent = formatTime(data.water.last_update);
        }

        // 4. Update Daily Averages
        if (data.averages) {
            const format = (val) => Math.round(parseFloat(val) || 0);
            document.getElementById('avg-temp').textContent = format(data.averages.avg_temp);
            document.getElementById('avg-humid').textContent = format(data.averages.avg_humid);
            document.getElementById('avg-moisture').textContent = format(data.averages.avg_moisture);
            document.getElementById('avg-co2').textContent = format(data.averages.avg_gas);
            document.getElementById('avg-ammonia').textContent = format(data.averages.avg_ammonia);
        }

    } catch (error) {
        console.error("Dashboard update error:", error);
    }
}

updateDashboard();
setInterval(updateDashboard, 1000); // 60000 seconds
</script>

<script>
const CHART_INTERVAL = 1800000; // 1800000

// --- SHARED OPTIONS FOR RESPONSIVENESS ---
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false, 
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

// 1. MOISTURE CHART
async function loadMoistureChart() {
    try {
        const response = await fetch("../functions/get_moisture_data.php");
        const data = await response.json();
        if (!Array.isArray(data) || data.length === 0) return;

        const labels = data.map(item => new Date(item.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })); 
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

// 2. TEMP & HUM CHART
async function loadTempHumChart() {
    try {
        const response = await fetch("../functions/get_temp_hum_data.php");
        const data = await response.json();
        if (!Array.isArray(data) || data.length === 0) return;

        const labels = data.map(item => new Date(item.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
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

// 3. GAS CHART
async function loadGasAmmoniaChart() {
    try {
        const response = await fetch("../functions/get_gas_ammonia_data.php");
        const result = await response.json();
        if (!result.gas || !result.ammonia) return;

        const gasLabels = result.gas.map(item => new Date(item.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
        const gasValues = result.gas.map(item => item.gas);
        const ammoniaValues = result.ammonia.map(item => item.ammonia);
        const ctx = document.getElementById("gasAmmoniaChart").getContext("2d");

        if (window.gasAmmoniaChartInstance) window.gasAmmoniaChartInstance.destroy();

        window.gasAmmoniaChartInstance = new Chart(ctx, {
            type: "line",
            data: {
                labels: gasLabels,
                datasets: [
                    { label: "CO₂ (%)", data: gasValues, borderColor: "#dc2626", backgroundColor: "rgba(220,38,38,0.2)", fill: true, tension: 0.4, borderWidth: 2, pointRadius: 2 },
                    { label: "Ammonia (%)", data: ammoniaValues, borderColor: "#9333ea", backgroundColor: "rgba(147,51,234,0.2)", fill: true, tension: 0.4, borderWidth: 2, pointRadius: 2 }
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

<script>
function showMsg(el, msg, success = true) {
    el.textContent = msg;
    el.className = success ? "text-green-600 text-sm pulse" : "text-red-600 text-sm pulse";
    el.style.opacity = 1;
    setTimeout(() => (el.style.opacity = 0), 2000);
}

// --- SOIL MOISTURE THRESHOLD FUNCTIONS (EXISTING) ---

async function loadThreshold() {
    try {
        const res = await fetch("../functions/get_soil_threshold.php");
        const val = (await res.text()).trim();
        document.getElementById("thresholdSelect").value = val;
    } catch { console.error("Failed to load soil threshold."); }
}

async function saveThreshold() {
    const val = document.getElementById("thresholdSelect").value;
    const res = await fetch("../functions/update_soil_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "threshold=" + val
    });
    if (res.ok) showMsg(document.getElementById("statusMsg"), "✅ Threshold saved to " + val + "%");
}

async function resetThreshold() {
    const res = await fetch("../functions/update_soil_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "threshold=55"
    });
    if (res.ok) {
        document.getElementById("thresholdSelect").value = "55";
        showMsg(document.getElementById("statusMsg"), "🔄 Reset to 55%", true);
    }
}

// --- CO2 THRESHOLD FUNCTIONS ---

document.addEventListener("DOMContentLoaded", () => {
    // 1. Load current value on page load
    loadCo2Threshold();

    // 2. Attach Click Events to the new buttons
    const saveBtn = document.getElementById("saveCo2ThresholdBtn");
    const resetBtn = document.getElementById("resetCo2ThresholdBtn");

    if (saveBtn) saveBtn.addEventListener("click", saveCo2Threshold);
    if (resetBtn) resetBtn.addEventListener("click", resetCo2Threshold);
});

async function loadCo2Threshold() {
    try {
        const res = await fetch("../functions/get_co2_threshold.php");
        const val = (await res.text()).trim();
        
        // Ensure the dropdown selects the correct value from DB
        const select = document.getElementById("co2ThresholdSelect");
        if(select) {
            select.value = val; 
        }
    } catch (err) { 
        console.error("Failed to load CO2 threshold:", err); 
    }
}

async function saveCo2Threshold() {
    const val = document.getElementById("co2ThresholdSelect").value;
    
    // Send to PHP
    const res = await fetch("../functions/update_co2_threshold.php", {
        method: "POST", 
        headers: { "Content-Type": "application/x-www-form-urlencoded" }, 
        body: "co2_threshold=" + val
    });

    if (res.ok) {
        showMsg(document.getElementById("co2StatusMsg"), "✅ Threshold saved: " + val + "%");
    } else {
        showMsg(document.getElementById("co2StatusMsg"), "❌ Error saving", true);
    }
}

async function resetCo2Threshold() {
    // ✅ FIXED: Changed "6" to "0.05" to match your new default
    const defaultVal = "0.05";
    
    const res = await fetch("../functions/update_co2_threshold.php", {
        method: "POST", 
        headers: { "Content-Type": "application/x-www-form-urlencoded" }, 
        body: "co2_threshold=" + defaultVal
    });

    if (res.ok) {
        document.getElementById("co2ThresholdSelect").value = defaultVal;
        showMsg(document.getElementById("co2StatusMsg"), "🔄 Reset to " + defaultVal + "%", true);
    }
}

// Helper function for status messages (if you don't have one)
function showMsg(element, text, isError = false) {
    if(!element) return;
    element.textContent = text;
    element.className = isError ? "text-red-500 text-sm font-bold" : "text-green-500 text-sm font-bold";
    setTimeout(() => { element.textContent = ""; }, 3000);
}

// --- TEMPERATURE THRESHOLD FUNCTIONS (NEW) ---

async function loadTempThreshold() {
    // Assuming a new PHP file for getting the Temperature threshold
    try {
        const res = await fetch("../functions/get_temp_threshold.php");
        const val = (await res.text()).trim();
        document.getElementById("tempThresholdSelect").value = val;
    } catch { console.error("Failed to load Temperature threshold."); }
}

async function saveTempThreshold() {
    const val = document.getElementById("tempThresholdSelect").value;
    // Assuming a new PHP file for updating the Temperature threshold
    const res = await fetch("../functions/update_temp_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "temp_threshold=" + val
    });
    if (res.ok) showMsg(document.getElementById("tempStatusMsg"), "✅ Temp Threshold saved to " + val + "%");
}

async function resetTempThreshold() {
    // Default value is 31%
    const res = await fetch("../functions/update_temp_threshold.php", {
        method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: "temp_threshold=31"
    });
    if (res.ok) {
        document.getElementById("tempThresholdSelect").value = "31";
        showMsg(document.getElementById("tempStatusMsg"), "🔄 Reset to 31%", true);
    }
}


// --- SPRINKLER DURATION FUNCTIONS (EXISTING) ---

async function loadDuration() {
    try {
        const res = await fetch("../functions/get_sprinkler_duration.php");
        const val = (await res.text()).trim();
        document.getElementById("durationSelect").value = val || "2000";
    } catch { document.getElementById("durationSelect").value = "2000"; }
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

document.addEventListener("DOMContentLoaded", () => {
    loadThreshold();
    loadCo2Threshold(); // NEW
    loadTempThreshold(); // NEW
    loadDuration();
    
    // Soil Moisture
    document.getElementById("saveThresholdBtn").addEventListener("click", saveThreshold);
    document.getElementById("resetThresholdBtn").addEventListener("click", resetThreshold);
    
    // CO2
    document.getElementById("saveCo2ThresholdBtn").addEventListener("click", saveCo2Threshold); // NEW
    document.getElementById("resetCo2ThresholdBtn").addEventListener("click", resetCo2Threshold); // NEW

    // Temperature
    document.getElementById("saveTempThresholdBtn").addEventListener("click", saveTempThreshold); // NEW
    document.getElementById("resetTempThresholdBtn").addEventListener("click", resetTempThreshold); // NEW

    // Sprinkler
    document.getElementById("saveDuration").addEventListener("click", saveDuration);
    document.getElementById("resetDurationBtn").addEventListener("click", resetDuration);
});

// RELAY CONTROL (UNCHANGED)
document.addEventListener("DOMContentLoaded", function() {
    const relayNames = { "RELAY_SHARED": "FAN", "RELAY_SOIL": "SPRINKLER" };
    const fanToggle = document.getElementById("fanToggle");
    const sprinklerToggle = document.getElementById("sprinklerToggle");

    if(fanToggle) fanToggle.addEventListener("change", function() { updateRelay("RELAY_SHARED", this.checked ? "ON" : "OFF"); });
    if(sprinklerToggle) sprinklerToggle.addEventListener("change", function() {
        const state = this.checked ? "ON" : "OFF";
        updateRelay("RELAY_SOIL", state);
        if (state === "ON") setTimeout(() => { sprinklerToggle.checked = false; updateRelay("RELAY_SOIL", "OFF"); }, 5000);
    });

    function updateRelay(relay, state) {
        fetch(`../functions/relay_control.php?relay=${relay}&state=${state}&mode=MANUAL`)
            .then(response => response.text())
            .then(data => {
                console.log(data);
                const name = relayNames[relay] || relay.replace("RELAY_", "");
                showNotification(`✅ ${name} turned ${state}`);
            })
            .catch(err => { console.error(err); showNotification("❌ Connection error"); });
    }

    function showNotification(message) {
        const notif = document.createElement("div");
        notif.textContent = message;
        notif.className = "fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md z-50";
        document.body.appendChild(notif);
        setTimeout(() => notif.remove(), 3000);
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