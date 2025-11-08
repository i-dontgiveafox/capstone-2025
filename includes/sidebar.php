<aside class="side-bar ml-[100%] fixed w-full h-screen px-6 flex flex-col justify-between border-r bg-white transition transition-all duration-500 md:w-4/12 lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]">
    <div>
        <div class="px-6 py-6 -mx-6">
            <a href="#" title="Home">
                <img src="../assets/images/logo.png" alt="Logo" class="w-32">
            </a>
        </div>

        <div class="mt-8 text-center">
            <img src="../assets/images/sidebar-image.png" alt="profile picture" class="rounded-full object-cover w-20 h-20 m-auto lg:w-28 lg:h-28">
            <h5 class="mt-4 text-xl font-semibold text-gray-600 block"><!--User name-->James</h5>
            <span class="text-gray-400 block mt-2"><!--Position-->Web Developer</span>
        </div>

        <ul class="space-y-2 tracking-wide">
            <li>
                <a href="#" class="relative px-4 py-3 flex items-center space-x-4 rounded-xl text-white bg-gradient-to-r from-pink-400 to-rose-400">
                    <span class="text-gray-700 group-hover:text-gray-700">Dashboard</span>
            </li>
            <li>
                <a href="#" class="relative px-4 py-3 flex items-center space-x-4 rounded-xl text-gray-600 group">
                    <span class="text-gray-700 group-hover:text-gray-700">Charts</span>
            </li>
            <li>
                <a href="#" class="relative px-4 py-3 flex items-center space-x-4 rounded-xl text-gray-600 group">
                    <span class="text-gray-700 group-hover:text-gray-700">Logs</span>
            </li>
        </ul>
    </div>

    <div class="px-6 pt-4 flex justify-between items-center border-t">
        <button class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group">
            <span class="text-gray-700 group-hover:text-gray-700">Logout</span>
    </div>

</aside>

<button class="button w-12 h-16 lg:hidden absolute right-0 z-20">
    <svg xmls="http://w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 my-auto">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<script>
    const sideBar = document.querySelector('.side-bar');
    const toggleSideBar = document.querySelector('.button');

    toggleSideBar.addEventListener('click', () => {
        sideBar.classList.toggle('ml-[-100%]');
    });
</script>
