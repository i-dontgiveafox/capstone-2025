<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - VermiCare</title>
    
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
                <p class="text-gray-600">Create your account</p>
            </div>

            <!-- Signup Form Card -->
            <div class="rounded-xl p-6 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>
                
                <div class="relative z-10">
                    <form action="../functions/signup-func.php" method="POST" class="space-y-4">
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline"><?=$_GET['error']?></span>
                            </div>
                        <?php } ?>

                        <?php if (isset($_GET['success'])) { ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline"><?=$_GET['success']?></span>
                            </div>
                        <?php } ?>

                        <!-- First Name -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">First Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                                    <i class='bx bx-user'></i>
                                </span>
                                <input type="text" 
                                       name="first_name"
                                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80"
                                       placeholder="John">
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Last Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                                    <i class='bx bx-user'></i>
                                </span>
                                <input type="text"
                                       name="last_name"
                                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80"
                                       placeholder="Doe">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                                    <i class='bx bx-envelope'></i>
                                </span>
                                <input type="email" 
                                       name="email"
                                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80"
                                       placeholder="john@example.com">
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
                                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80"
                                       placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                                    <i class='bx bx-lock-alt'></i>
                                </span>
                                <input type="password" 
                                       name="confirm_password"
                                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80"
                                       placeholder="••••••••">
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer">
                                    <i class='bx bx-hide' id="togglePassword"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Sign Up Button -->
                        <button type="submit" 
                                class="w-full bg-[#1e1e1e] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#B6FC67] hover:text-black transition duration-300 mt-6">
                            Create Account
                        </button>

                        <!-- Login Link -->
                        <div class="text-center mt-4">
                            <a href="login.php" class="text-gray-600 hover:text-[#1e1e1e] text-sm">
                                Already have an account? <span class="font-semibold">Log in</span>
                            </a>
                        </div>

                        <!-- Optional: Social Sign Up 
                        <div class="mt-6 text-center">
                            <span class="text-gray-500 text-sm">Or sign up with</span>
                            <div class="flex justify-center space-x-4 mt-3">
                                <a href="../functions/google-func.php" 
                                   class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 bg-white/80">
                                    <img src="../assets/icons/google.png" alt="Google" class="w-5 h-5 mr-2">
                                    <span class="text-gray-600">Google</span>
                                </a>
                            </div>
                        </div>-->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('input[name="confirm_password"]');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the icon
            this.classList.toggle('bx-hide');
            this.classList.toggle('bx-show');
        });
    </script>
</body>
</html>