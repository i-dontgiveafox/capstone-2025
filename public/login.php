<?php
require_once '../functions/google-func.php';
$google_login_url = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-gray-800">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md flex flex-col items-center relative animate-fade-in">
        <!-- Logo -->
        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/React-icon.svg" alt="Logo" class="w-16 h-16 mb-4 rounded-full shadow-lg">
        <h3 class="text-2xl font-bold mb-6 text-center tracking-wide">Login</h3>

        <?php if (isset($_GET['error'])) { ?>
            <b class="text-red-600 mb-2 block text-center transition-opacity duration-500 opacity-100"><?=$_GET['error']?></b>
        <?php } elseif (isset($_GET['success'])) { ?>
            <b id="successMsg" class="text-green-600 mb-2 block text-center transition-opacity duration-500 opacity-100"><?=$_GET['success']?></b>
            <span id="loginPrompt" class="mb-2 block text-center text-green-600 text-base font-bold hidden">Login to your account</span>
        <?php } ?>

        <form action="../functions/login-func.php" method="POST" class="w-full flex flex-col gap-4">
            <div class="relative">
                <label class="block mb-1 font-medium">Email</label>
                <span class="absolute left-3 top-9 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12l-4-4-4 4m0 0v6m8-6v6" /></svg>
                </span>
                <input type="text" name="email" class="w-full pl-10 pr-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400" required>
            </div>
            <div class="relative">
                <label class="block mb-1 font-medium">Password</label>
                <span class="absolute left-3 top-9 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 0v2m0 4h.01" /></svg>
                </span>
                <input type="password" name="password" id="password" class="w-full pl-10 pr-10 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400" required>
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-9 text-gray-400 focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0a9 9 0 0118 0c0 5-4 9-9 9s-9-4-9-9z" /></svg>
                </button>
            </div>
            <button type="submit" class="bg-black text-white py-2 rounded hover:bg-gray-800 font-semibold transition-all duration-300 flex items-center justify-center">
                <span id="loginText">Login</span>
                <span id="spinner" class="hidden ml-2 animate-spin">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                </span>
            </button>
            <div class="flex justify-between text-sm">
                <a href="signup.php" class="text-blue-600 hover:underline">Sign Up</a>
                <a href="forgot-password.php" class="text-blue-600 hover:underline">Forgot Password?</a>
            </div>
        </form>

        <hr class="my-6 w-full">
        <a href="<?php echo $google_login_url; ?>" class="w-full flex justify-center">
            <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" 
                 alt="Sign in with Google" class="h-10 transition-transform duration-300 hover:scale-105">
        </a>
    </div>

    <script>
    // Fade-in animation for modal
    document.querySelector('body').classList.add('animate-fade-in');

    // Fade out success message and show login prompt after 5 seconds
    const successMsg = document.getElementById('successMsg');
    const loginPrompt = document.getElementById('loginPrompt');
    if (successMsg && loginPrompt) {
        setTimeout(() => {
            successMsg.classList.add('hidden');
            loginPrompt.classList.remove('hidden');
        }, 5000);
    }

    // Show/hide password toggle
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-9a9 9 0 0118 0c0 1.657-.336 3.236-.938 4.675M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0a9 9 0 0118 0c0 5-4 9-9 9s-9-4-9-9z" />';
        }
    }

    // Show spinner on login button when submitting
    document.querySelector('form').addEventListener('submit', function(e) {
        document.getElementById('loginText').classList.add('hidden');
        document.getElementById('spinner').classList.remove('hidden');
    });
    </script>
</body>
</html>
