<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-gray-800">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md flex flex-col items-center relative animate-fade-in">
        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/React-icon.svg" alt="Logo" class="w-16 h-16 mb-4 rounded-full shadow-lg">
        <h3 class="text-2xl font-bold mb-6 text-center tracking-wide">Reset Password</h3>
        <?php
        include_once '../config/db_conn.php';
        date_default_timezone_set('Asia/Manila');

        $message = '';
        $messageType = '';
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
                            echo '<script>setTimeout(function(){window.location.href = "login.php?success=Password updated successfully";}, 3000);</script>';
                        } else {
                            $message = 'Failed to update password. Try again.';
                            $messageType = 'error';
                        }
                    }
                }
                if ($message) {
                    echo '<b class="' . ($messageType === 'error' ? 'text-red-600' : 'text-green-600') . ' mb-2 block text-center transition-opacity duration-500 opacity-100">' . htmlspecialchars($message) . '</b>';
                }
                if (!isset($showForm) || $showForm !== false) {
        ?>
        <form method="POST" class="w-full flex flex-col gap-4">
            <div class="relative">
                <label class="block mb-1 font-medium">New Password:</label>
                <span class="absolute left-3 top-9 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 0v2m0 4h.01" /></svg>
                </span>
                <input type="password" name="new_password" required class="w-full pl-10 pr-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <div class="relative">
                <label class="block mb-1 font-medium">Confirm Password:</label>
                <span class="absolute left-3 top-9 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 0v2m0 4h.01" /></svg>
                </span>
                <input type="password" name="confirm_password" required class="w-full pl-10 pr-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
            </div>
            <button type="submit" class="bg-black text-white py-2 rounded hover:bg-gray-800 font-semibold transition-all duration-300 flex items-center justify-center">
                <span id="updateText">Update Password</span>
                <span id="spinner" class="hidden ml-2 animate-spin">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                </span>
            </button>
        </form>
        <?php
                }
            } else {
                echo '<b class="text-red-600 mb-2 block text-center transition-opacity duration-500 opacity-100">Invalid or expired token</b>';
            }
        } else {
            echo '<b class="text-red-600 mb-2 block text-center transition-opacity duration-500 opacity-100">No token provided</b>';
        }
        ?>
        <?php if (!isset($showForm) || $showForm !== false) { ?>
        <div class="mt-4 w-full text-center">
            <a href="login.php" class="text-blue-600 hover:underline">Back to Login</a>
        </div>
        <?php } ?>
    </div>
    <script>
    // Fade-in animation for modal
    document.querySelector('body').classList.add('animate-fade-in');
    // Show spinner on button when submitting
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            document.getElementById('updateText').classList.add('hidden');
            document.getElementById('spinner').classList.remove('hidden');
        });
    }
    </script>
</body>
</html>
