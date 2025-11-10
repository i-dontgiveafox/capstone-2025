<header class="">
    <nav class="fixed top-0 left-0 w-full z-50 flex bg-[#1e1e1e] text-white justify-between items-center py-2 pr-10 shadow-md">
        <div class="flex items-center gap-2 ml-10">
            <span class="text-xl font-semibold text-white"><span class="font-light">Vermi</span><span class="font-bold">Care</span>
        </div>


        <div class="nav-links z-50 duration-500 hidden md:flex md:static absolute bg-[#1e1e1e] md:min-h-fit min-h-[60vh] left-0 top-0 md:top-auto md:w-auto w-full items-center px-5 text-white">
            <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#"><i class='bx bxs-dashboard'></i> Dashboard</a></li>
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#"><i class='bx bxs-chart' ></i> Charts</a></li>
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#"><i class='bx bxs-report'></i> Logs</a></li>
                <!-- <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#">Profile</a></li>-->
                <!-- mobile: logout inside the collapsible nav-links -->
                <li><a id="mobile-logout" class="md:hidden hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full block cursor-pointer"><i class='bx bx-log-out'></i> Logout</a></li>
            </ul>
            
            
        </div>

        <div class="flex items-center gap-2">
            <!-- Notification Button -->
            <div class="relative">
            <button id="notifBtn" class="text-white px-3 py-1 ml-2 rounded-full hover:bg-[#B6FC67] hover:text-black relative">
                <ion-icon class="text-2xl" name="notifications-outline"></ion-icon>
                <span id="notifBadge" class="hidden absolute top-0 right-0 bg-red-500 text-xs text-white rounded-full px-1">3</span>
            </button>

            <!-- Notification Dropdown -->
            <div id="notifDropdown"
                class="hidden absolute right-0 mt-2 w-80 bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 rounded-xl shadow-lg overflow-hidden z-50">
                
                <div class="flex justify-between items-center px-4 py-2 border-b border-gray-200">
                    <h3 class="text-gray-800 font-semibold">Notifications</h3>
                    <button id="markAllRead" class="text-sm text-blue-600 hover:underline">Mark all as read</button>
                </div>
                
                <!-- Scrollable list -->
                <div class="max-h-64 overflow-y-auto">
                    <div class="px-4 py-3 hover:bg-gray-100 border-b border-gray-200 cursor-pointer">
                        <p class="text-sm text-gray-700">🌿 Irrigation activated automatically (08:00 AM)</p>
                        <p class="text-xs text-gray-500">2 minutes ago</p>
                    </div>
                    <div class="px-4 py-3 hover:bg-gray-100 border-b border-gray-200 cursor-pointer">
                        <p class="text-sm text-gray-700">💨 Fan turned on due to high temperature</p>
                        <p class="text-xs text-gray-500">10 minutes ago</p>
                    </div>
                    <div class="px-4 py-3 hover:bg-gray-100 border-b border-gray-200 cursor-pointer">
                        <p class="text-sm text-gray-700">💧 Soil moisture dropped below threshold</p>
                        <p class="text-xs text-gray-500">30 minutes ago</p>
                    </div>
                    <!-- You can dynamically add more items here later -->
                </div>
            </div>
            </div>

            <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-3xl z-15 cursor-pointer md:hidden"></ion-icon>
            <!-- desktop: show logout on the far right, hidden on small screens -->
            <a id="desktop-logout" class="hidden md:inline-block hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full ml-4 cursor-pointer"><i class='bx bx-log-out'></i> Logout</a>
        </div>
        

        <script>
            const navLinks = document.querySelector('.nav-links');
            function onToggleMenu(e) {
                e.name = e.name === 'menu' ? 'close' : 'menu';
                // Toggle visibility on mobile by toggling the 'hidden' class
                navLinks.classList.toggle('hidden');
            }
            // Logout confirmation modal logic
            function openLogoutModal(targetUrl) {
                const modal = document.getElementById('logout-modal');
                modal.classList.remove('hidden');
                // store target URL (if needed in future)
                modal.dataset.target = targetUrl || '../public/logout.php';
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
                    // Ensure mobile menu is closed for clarity then open modal
                    navLinks.classList.add('hidden');
                    openLogoutModal('../public/logout.php');
                });

                if (confirmBtn) confirmBtn.addEventListener('click', function () {
                    const modal = document.getElementById('logout-modal');
                    const target = modal && modal.dataset.target ? modal.dataset.target : '../public/logout.php';
                    window.location.href = target;
                });

                if (cancelBtn) cancelBtn.addEventListener('click', function () {
                    closeLogoutModal();
                });
            });

        </script>

        <!-- Logout confirmation modal (hidden by default) -->
        <div id="logout-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 bg-opacity-20">
            <div class="bg-white rounded-lg p-6 w-11/12 max-w-md">
                <h3 class="text-lg font-semibold mb-2 text-gray-900">Confirm logout</h3>
                <p class="text-sm text-gray-700 mb-4">Are you sure you want to logout?</p>
                <div class="flex justify-end gap-3">
                    <button id="cancel-logout" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-300">Cancel</button>
                    <button id="confirm-logout" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600">Logout</button>
                </div>
            </div>
        </div>

        <!-- For notification dropdown) -->
        <script>
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
            notifDropdown.classList.add('hidden');
            }
        });
        </script>
    </nav>
</header>
