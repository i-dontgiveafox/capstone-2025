<header class="">
    <nav class="flex bg-[#1e1e1e] text-white justify-between items-center w-[100%] md:min-w-sm mx-auto py-6 pr-10">
        <div class="flex items-center gap-2 ml-10">
            <span class="text-xl font-semibold text-white"><span class="font-light">Vermi</span><span class="font-bold">Care</span>
        </div>


        <div class="nav-links duration-500 md:static absolute bg-[#1e1e1e] md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5 text-white">
            <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#">Dashboard</a></li>
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#">Charts</a></li>
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#">Logs</a></li>
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#">Profile</a></li>
                <!-- mobile: logout inside the collapsible nav-links -->
                <li><a id="mobile-logout" class="md:hidden hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full block cursor-pointer">Logout</a></li>
            </ul>
            
            
        </div>

        <div class="flex items-center gap-2">
            <!--<button class="text-white p-4 mr-10 rounded-full hover:bg-[#87acec]">
                <ion-icon class="text-2xl" name="notifications-outline"></ion-icon>
            </button>-->
            <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-3xl cursor-pointer md:hidden"></ion-icon>
            <!-- desktop: show logout on the far right, hidden on small screens -->
            <a id="desktop-logout" class="hidden md:inline-block hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full ml-4 cursor-pointer">Logout</a>
        </div>
        

        <script>
            const navLinks = document.querySelector('.nav-links');
            function onToggleMenu(e) {
                e.name = e.name === 'menu' ? 'close' : 'menu'
                navLinks.classList.toggle('top-[0%]');
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
                    // close mobile menu for clarity then open modal
                    navLinks.classList.remove('top-[0%]');
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
        <div id="logout-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-11/12 max-w-md">
                <h3 class="text-lg font-semibold mb-2 text-gray-900">Confirm logout</h3>
                <p class="text-sm text-gray-700 mb-4">Are you sure you want to logout?</p>
                <div class="flex justify-end gap-3">
                    <button id="cancel-logout" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                    <button id="confirm-logout" class="px-4 py-2 rounded bg-[#B6FC67] hover:bg-[#9fec4f]">Logout</button>
                </div>
            </div>
        </div>
    </nav>
</header>
