<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - VermiCare</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
    <link rel="stylesheet" href="../index.css">
</head>
<body class="flex min-h-screen bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 bg-fixed">
    <div class="container max-w-md mx-auto px-4 py-8 flex-1 flex items-center justify-center">
        <div class="w-full">
            <div class="text-center mb-8">
                <img src="../assets/icons/worm.png" alt="VermiCare Logo" class="w-20 h-20 mx-auto mb-4">
                <h1 class="text-3xl font-semibold mb-2">
                    <span class="font-light">Vermi</span><span class="font-bold">Care</span>
                </h1>
                <p class="text-gray-600">Reset your password</p>
            </div>

            <div class="rounded-xl p-6 relative shadow border border-white/20">
                <div class="absolute inset-0 rounded-xl" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,1);"></div>
                <div class="relative z-10">

                    <?php
                    include_once '../config/db_conn.php';
                    date_default_timezone_set('Asia/Manila');

                    $message = '';
                    $messageType = '';
                    $showForm = true;

                    if (isset($_GET['token'])) {
                        $token = $_GET['token'];
                        $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
                        $stmt->execute([$token]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($user && strtotime($user['reset_expiry']) > time()) {
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $new_password = trim($_POST['new_password']);
                                $confirm_password = trim($_POST['confirm_password']);
                                if (empty($new_password) || empty($confirm_password)) {
                                    $message = 'All fields are required.';
                                    $messageType = 'error';
                                } elseif ($new_password !== $confirm_password) {
                                    $message = 'Passwords do not match.';
                                    $messageType = 'error';
                                } else {
                                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                                    $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
                                    $update->execute([$hashed, $token]);
                                    if ($update->rowCount() > 0) {
                                        $message = 'Password updated successfully! Redirecting to login...';
                                        $messageType = 'success';
                                        $showForm = false;
                                        echo '<script>setTimeout(function(){window.location.href = "login.php?success=Password updated successfully";}, 2500);</script>';
                                    } else {
                                        $message = 'Failed to update password. Try again.';
                                        $messageType = 'error';
                                    }
                                }
                            }
                        } else {
                            $message = 'Invalid or expired token';
                            $messageType = 'error';
                            $showForm = false;
                        }
                    } else {
                        $message = 'No token provided';
                        $messageType = 'error';
                        $showForm = false;
                    }

                    if ($message) {
                        echo '<div class="mb-4">';
                        if ($messageType === 'error') {
                            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">' . htmlspecialchars($message) . '</div>';
                        } else {
                            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">' . htmlspecialchars($message) . '</div>';
                        }
                        echo '</div>';
                    }
                    ?>

                    <?php if ($showForm) { ?>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">New Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                                    <i class='bx bx-lock-alt'></i>
                                </span>
                                <input type="password" name="new_password" class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80" placeholder="At least 6 characters" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">
                                    <i class='bx bx-lock-alt'></i>
                                </span>
                                <input type="password" name="confirm_password" class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#B6FC67] bg-white/80" placeholder="Repeat new password" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#B6FC67] text-black py-2 rounded-lg font-semibold hover:bg-[#a0e853] transition-all duration-300 flex items-center justify-center">
                            <span id="updateText">Update Password</span>
                            <span id="spinner" class="hidden ml-2 animate-spin">
                                <svg class="h-5 w-5 text-black" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                            </span>
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <p class="text-gray-600 text-sm">Remember your password? <a href="login.php" class="text-[#1e1e1e] hover:text-[#B6FC67] font-semibold">Back to Login</a></p>
                    </div>
                    <?php } else { ?>
                    <div class="text-center">
                        <a href="login.php" class="inline-block bg-[#B6FC67] text-black py-2 px-6 rounded-lg font-semibold hover:bg-[#a0e853] transition-all duration-300">Back to Login</a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Show spinner on button when submitting
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const txt = document.getElementById('updateText');
            const spinner = document.getElementById('spinner');
            if (txt) txt.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
        });
    }
    </script>
</body>
</html>
