<?php
require_once '../functions/google-func.php';
$google_login_url = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VermiCare</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
    <link rel="stylesheet" href="../index.css">
</head>

<body class="flex min-h-screen bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 bg-fixed">
    <div class="container max-w-md mx-auto px-4 py-8 flex-1 flex items-center justify-center">
        <div class="w-full">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <img src="../assets/icons/worm.png" alt="VermiCare Logo" class="w-20 h-20 mx-auto mb-4">
                <h1 class="text-3xl font-semibold mb-2">
                    <span class="font-light">Vermi</span><span class="font-bold">Care</span>
                </h1>
                <p class="text-gray-600">Welcome back</p>
            </div>

            <!-- Login Form Card -->
            <div class="rounded-xl p-6 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>
                
                <div class="relative z-10">

                    <?php if (isset($_GET['error'])) { ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?=$_GET['error']?></span>
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET['success'])) { ?>
                        <div id="successMsg" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?=$_GET['success']?></span>
                        </div>
                        <div id="loginPrompt" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
                            <span class="block sm:inline">Login to your account</span>
                        </div>
                    <?php } ?>

                    <form action="../functions/login-func.php" method="POST" class="space-y-4">
                        <!-- Email -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                                    <i class='bx bx-envelope'></i>
                                </span>
                                <input type="text" 
                                       name="email" 
                                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80"
                                       placeholder="Admin"
                                       required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                                    <i class='bx bx-lock-alt'></i>
                                </span>
                                <input type="password" 
                                       name="password"
                                       id="password"
                                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80"
                                       placeholder="••••••••"
                                       required>
                            </div>
                        </div>

                        <!-- Remember Me and Forgot Password -->
                        <div class="flex justify-center items-center text-sm">
                            <div class="flex items-center">
                               <a href="forgot-password.php" class="text-[#1e1e1e] hover:text-[#B6FC67]">Forgot password?</a>
                            </div>
                            
                        </div>

                        <!-- Login Button -->
                        <button type="submit" 
                                class="w-full bg-[#1e1e1e] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#B6FC67] hover:text-black transition duration-300 mt-6 flex items-center justify-center">
                            <span id="loginText">Login</span>
                            <span id="spinner" class="hidden ml-2">
                                <i class='bx bx-loader-alt animate-spin'></i>
                            </span>
                        </button>

                        <!-- Sign Up Link -->
                        <!--<div class="text-center mt-4">-->
                        <!--    <a href="signup.php" class="text-gray-600 hover:text-[#1e1e1e] text-sm">-->
                        <!--        Don't have an account? <span class="font-semibold">Sign up</span>-->
                        <!--    </a>-->
                        <!--</div>-->

                        <!-- Social Login -->
                        <!--<div class="mt-6 text-center">-->
                        <!--    <span class="text-gray-500 text-sm">Or continue with</span>-->
                        <!--    <div class="flex justify-center space-x-4 mt-3">-->
                        <!--        <a href="<?php echo $google_login_url; ?>" class="w-full flex justify-center">-->
                        <!--            <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" -->
                        <!--                alt="Sign in with Google" class="h-10 transition-transform duration-300 hover:scale-105">-->
                        <!--        </a>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Fade-in animation for modal
    document.querySelector('body').classList.add('animate-fade-in');

    // (password visibility toggle removed - using single lock icon only)

    // Success message auto-hide with smooth transition
    const successMsg = document.getElementById('successMsg');
    const loginPrompt = document.getElementById('loginPrompt');
    
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.transition = 'opacity 0.5s ease-in-out';
            successMsg.style.opacity = '0';
            setTimeout(() => {
                successMsg.style.display = 'none';
                if (loginPrompt) {
                    loginPrompt.style.display = 'block';
                    loginPrompt.style.transition = 'opacity 0.5s ease-in-out';
                    setTimeout(() => {
                        loginPrompt.style.opacity = '1';
                    }, 50);
                }
            }, 500);
        }, 3000);
    }

    // Form submission handling with loading spinner
    document.querySelector('form').addEventListener('submit', function() {
        const loginText = document.getElementById('loginText');
        const spinner = document.getElementById('spinner');
        
        loginText.classList.add('hidden');
        spinner.classList.remove('hidden');
    });
    </script>
</body>
</html>
