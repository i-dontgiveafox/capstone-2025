<header class="">
    <nav class="flex bg-[#1e1e1e] text-white justify-between items-center w-[100%] min-w-sm mx-auto py-6 pr-10 rounded-b-[150px]">
        <div class="flex items-center gap-2 ml-10">
            <img class="h-8 w-8" src="assets/icons/plant-pot-svg.svg" alt="System logo">
            <span class="text-xl font-semibold text-white">VermiCare</span>
        </div>


        <div class="nav-links duration-500 md:static absolute bg-[#1e1e1e] md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5 text-white">
            <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#">Dashboard</a></li>
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#">Logs</a></li>
                <li><a class="hover:text-black hover:bg-[#B6FC67] px-4 py-3 rounded-full" href="#">Profile</a></li>
            </ul>
        </div>

        <div class="flex items-center gap-2">
            <!--<button class="text-white p-4 mr-10 rounded-full hover:bg-[#87acec]">
                <ion-icon class="text-2xl" name="notifications-outline"></ion-icon>
            </button>-->
            <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-3xl cursor-pointer md:hidden"></ion-icon>
        </div>

        <script>
            const navLinks = document.querySelector('.nav-links');
            function onToggleMenu(e) {
                e.name = e.name === 'menu' ? 'close' : 'menu'
                navLinks.classList.toggle('top-[0%]');
            }
        </script>
    </nav>
</header>
