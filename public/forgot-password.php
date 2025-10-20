<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-gray-800">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm flex flex-col items-center relative animate-fade-in">
        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/React-icon.svg" alt="Logo" class="w-16 h-16 mb-4 rounded-full shadow-lg">
        <h3 class="text-2xl font-bold mb-6 text-center tracking-wide">Forgot Password</h3>

        <?php
        $showForm = true;
        if (isset($_GET['success'])) {
            echo '<b class="text-green-600 mb-2 block text-center transition-opacity duration-500 opacity-100">' . $_GET['success'] . '</b>';
            $showForm = false;
        } elseif (isset($_GET['error'])) {
            echo '<b class="text-red-600 mb-2 block text-center transition-opacity duration-500 opacity-100">' . $_GET['error'] . '</b>';
        }
        if ($showForm) {
        ?>
        <form action="../functions/forgot-password-func.php" method="POST" class="w-full flex flex-col gap-4">
            <div class="relative">
                <label class="block mb-1 font-medium">Enter your email:</label>
                <span class="absolute left-3 top-9 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12l-4-4-4 4m0 0v6m8-6v6" /></svg>
                </span>
                <input type="email" name="email" required class="w-full pl-10 pr-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <button type="submit" class="bg-black text-white py-2 rounded hover:bg-gray-800 font-semibold transition-all duration-300 flex items-center justify-center">
                <span id="sendText">Send Reset Link</span>
                <span id="spinner" class="hidden ml-2 animate-spin">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                </span>
            </button>
        </form>
        <div class="mt-4 w-full text-center">
            <a href="login.php" class="text-blue-600 hover:underline">Back to Login</a>
        </div>
        <?php } ?>
    </div>
    <script>
    // Fade-in animation for modal
    document.querySelector('body').classList.add('animate-fade-in');
    // Show spinner on button when submitting
    document.querySelector('form').addEventListener('submit', function(e) {
        document.getElementById('sendText').classList.add('hidden');
        document.getElementById('spinner').classList.remove('hidden');
    });
    </script>
</body>
</html>
