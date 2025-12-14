<header class="">
    <nav class="fixed top-0 left-0 w-full z-50 flex bg-[#1e1e1e] text-white justify-between items-center pr-6 shadow-md h-16">
        
        <div class="h-full flex items-center gap-2 ml-4 md:ml-8">
            <span class="text-xl md:text-2xl font-semibold text-white flex items-center tracking-wide">
                <span class="font-light">Vermi</span><span class="font-bold">Care</span>
            </span>
        </div>

        <div id="nav-links" class="nav-links duration-300 absolute md:static left-0 top-[-500px] md:top-auto w-full md:w-auto bg-[#1e1e1e] md:bg-transparent z-40 px-5 pb-6 md:pb-0 shadow-xl md:shadow-none">
            <ul class="flex md:flex-row flex-col md:items-center md:gap-6 gap-6 border-t border-gray-700 md:border-none pt-8 md:pt-0 md:h-full">
                <li>
                    <a class="flex items-center gap-3 hover:bg-[#B6FC67] hover:text-black px-4 py-3 md:py-0 md:h-10 rounded-full transition-colors text-sm md:text-base font-medium" href="index.php">
                        <i class='bx bxs-dashboard text-lg md:text-xl relative top-[1px]'></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 hover:bg-[#B6FC67] hover:text-black px-4 py-3 md:py-0 md:h-10 rounded-full transition-colors text-sm md:text-base font-medium" href="view_logs.php">
                        <i class='bx bxs-report text-lg md:text-xl relative top-[1px]'></i> Logs
                    </a>
                </li>
                <li>
                    <a id="mobile-logout" class="md:hidden flex items-center gap-3 text-red-400 hover:bg-red-500/10 px-4 py-3 rounded-full cursor-pointer transition-colors text-sm md:text-base font-medium">
                        <i class='bx bx-log-out text-lg md:text-xl'></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex items-center gap-4 z-50 h-full">
            
            <div class="relative flex items-center h-full">
                <button id="notifBtn" class="text-white px-2 py-2 rounded-full hover:bg-[#333] transition-colors relative flex items-center justify-center" title="Notifications">
                    <ion-icon class="text-2xl" name="notifications-outline"></ion-icon>
                    <span id="notifBadge" class="hidden absolute top-1 right-1 bg-red-500 text-[10px] font-bold text-white rounded-full h-4 w-4 flex items-center justify-center animate-pulse">0</span>
                </button>

                <!-- Settings button placed beside notifications -->
                <button id="settingsBtn" class="text-white ml-2 px-2 py-2 rounded-full hover:bg-[#333] transition-colors relative flex items-center justify-center" title="Settings">
                    <ion-icon class="text-2xl" name="settings-outline"></ion-icon>
                </button>

                <div id="notifDropdown" class="hidden absolute right-0 top-14 mt-2 w-80 bg-white rounded-xl shadow-2xl overflow-hidden z-50 text-gray-800 ring-1 ring-black ring-opacity-5">
                    <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-sm font-bold text-gray-700">Notifications</h3>
                    </div>
                    
                    <div id="notifList" class="max-h-64 overflow-y-auto">
                        <div class="px-4 py-3 text-center text-gray-500 text-sm">
                            No new alerts.
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:hidden text-3xl cursor-pointer flex items-center text-white hover:text-[#B6FC67] transition-colors">
                <ion-icon onclick="onToggleMenu(this)" name="menu"></ion-icon>
            </div>

            <a id="desktop-logout" class="hidden md:inline-flex items-center gap-2 text-white hover:text-[#B6FC67] font-medium text-base cursor-pointer transition-colors">
                <i class='bx bx-log-out text-2xl'></i> Logout
            </a>
        </div>
    </nav>

    <div id="logout-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-sm shadow-2xl transform transition-all scale-100">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class='bx bx-log-out text-2xl text-red-600'></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-gray-900">Logout?</h3>
                <p class="text-sm text-gray-500 mt-2">Are you sure you want to end your session?</p>
            </div>
            <div class="mt-6 flex gap-3">
                <button id="cancel-logout" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm">Cancel</button>
                <button id="confirm-logout" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:text-sm">Logout</button>
            </div>
        </div>
    </div>
</header>

<script>
    // --- MENU TOGGLE LOGIC ---
    const navLinks = document.getElementById('nav-links');

    function onToggleMenu(icon) {
        icon.name = icon.name === 'menu' ? 'close' : 'menu';
         if (navLinks.classList.contains('top-[-500px]')) {
            navLinks.classList.remove('top-[-500px]');
            navLinks.classList.add('top-16'); 
        } else {
            navLinks.classList.add('top-[-500px]');
            navLinks.classList.remove('top-16');
        }
    }

    // --- LOGOUT MODAL LOGIC ---
    function openLogoutModal(targetUrl) {
        const modal = document.getElementById('logout-modal');
        modal.classList.remove('hidden');
        modal.dataset.target = targetUrl || 'logout.php'; 
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logout-modal');
        if (modal) modal.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const desktop = document.getElementById('desktop-logout');
        const mobile = document.getElementById('mobile-logout');
        const confirmBtn = document.getElementById('confirm-logout');
        const cancelBtn = document.getElementById('cancel-logout');

        if (desktop) desktop.addEventListener('click', function (e) {
            e.preventDefault();
            openLogoutModal('../public/logout.php');
        });

        if (mobile) mobile.addEventListener('click', function (e) {
            e.preventDefault();
            const icon = document.querySelector('ion-icon[name="close"]');
            if(icon) onToggleMenu(icon);
            openLogoutModal('../public/logout.php');
        });

        if (confirmBtn) confirmBtn.addEventListener('click', function () {
            const modal = document.getElementById('logout-modal');
            window.location.href = modal.dataset.target || '../public/logout.php';
        });

        if (cancelBtn) cancelBtn.addEventListener('click', closeLogoutModal);
        
        // Start Fetching Notifications (Every 5 seconds)
        setInterval(fetchNotifications, 5000); 
        fetchNotifications(); 

        // Settings button navigation
        const settingsBtn = document.getElementById('settingsBtn');
        if (settingsBtn) settingsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '../public/update-credentials.php';
        });
    });

    // --- NOTIFICATION INTERACTION LOGIC ---
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const badge = document.getElementById('notifBadge');
    const list = document.getElementById('notifList');

    notifBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notifDropdown.classList.toggle('hidden');
        
        // 1. Hide Badge Visually
        if (!badge.classList.contains('hidden')) {
            badge.classList.add('hidden');
            // 2. Tell Database to Mark as Read
            fetch('../functions/mark_as_read.php'); 
        }
    });

    document.addEventListener('click', (e) => {
        if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
    });

    // --- HELPER: FORMAT DATE & TIME ---
    function formatNotifDate(timestamp) {
        if (!timestamp) return '';
        // Create date object
        const date = new Date(timestamp);
        
        // If invalid date, return original string
        if (isNaN(date.getTime())) return timestamp;

        // Format: "Dec 14 • 9:30 AM" (Manila Time)
        const datePart = date.toLocaleDateString('en-US', {
            timeZone: 'Asia/Manila',
            month: 'short', 
            day: 'numeric'
        });
        
        const timePart = date.toLocaleTimeString('en-US', {
            timeZone: 'Asia/Manila',
            hour: 'numeric', 
            minute: '2-digit', 
            hour12: true
        });

        return `${datePart} • ${timePart}`;
    }

    // --- FETCH DATA LOGIC ---
    function fetchNotifications() {
        fetch('../functions/fetch_notifications.php')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notifList');
                
                const unreadCount = data.filter(item => item.is_read == 0).length;

                if (unreadCount > 0) {
                    badge.innerText = unreadCount;
                    if (notifDropdown.classList.contains('hidden')) {
                        badge.classList.remove('hidden');
                    }
                } else {
                    badge.classList.add('hidden');
                }

                list.innerHTML = ''; 

                if (data.length === 0) {
                    list.innerHTML = `<div class="px-4 py-3 text-center text-gray-500 text-sm">No new alerts.</div>`;
                    return;
                }

                data.forEach(alert => {
                    let iconColor = alert.type === 'gas' ? 'text-red-500' : 'text-blue-500';
                    let iconClass = alert.type === 'gas' ? 'bxs-hot' : 'bxs-droplet';
                    
                    let bgClass = alert.is_read == 1 ? 'bg-gray-50 opacity-75' : 'bg-white';
                    let textClass = alert.is_read == 1 ? 'font-normal text-gray-600' : 'font-bold text-gray-900';

                    // Use the helper function to format the time
                    const formattedTime = formatNotifDate(alert.time);

                    const item = `
                        <div class="px-4 py-3 ${bgClass} hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-0 flex items-start gap-3 transition-colors">
                            <i class='bx ${iconClass} ${iconColor} text-xl mt-1'></i>
                            <div>
                                <p class="text-sm ${textClass}">${alert.message}</p>
                                <p class="text-xs text-gray-500 mt-1">${formattedTime}</p>
                            </div>
                        </div>
                    `;
                    list.innerHTML += item;
                });
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }
</script>