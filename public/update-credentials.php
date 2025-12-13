<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit;
}

require_once __DIR__ . '/../config/db_conn.php';

$userId = $_SESSION['user_id'];

// Fetch current user data
$user = [];
try {
    // Try to fetch with recovery_email first
    $stmt = $conn->prepare('SELECT id, first_name, last_name, email, password, recovery_email FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new Exception('User not found');
    }
} catch (Exception $e) {
    // Fallback: fetch without recovery_email if column doesn't exist yet
    try {
        $stmt = $conn->prepare('SELECT id, first_name, last_name, email, password FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $user['recovery_email'] = null; // Add null default for recovery_email
        } else {
            throw new Exception('User not found');
        }
    } catch (Exception $e2) {
        $error = 'Unable to load user data.';
        $user = ['email' => '', 'recovery_email' => null];
    }
}

$success = '';
$error = $error ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Normalize inputs
    $new_username = isset($_POST['new_username']) ? trim($_POST['new_username']) : '';
    $recovery_email = isset($_POST['recovery_email']) ? trim($_POST['recovery_email']) : '';
    $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Basic validation
    if (empty($current_password)) {
        $error = 'Current password is required to make changes.';
    } else if (!password_verify($current_password, $user['password'])) {
        $error = 'Current password is incorrect.';
    } else {
        // We will accumulate update fields and run one UPDATE statement
        $updates = [];
        $params = [];

        // Username update (optional). Note: usernames are stored in the `email` column.
        if (!empty($new_username) && $new_username !== $user['email']) {
            // Reject values containing '@' or whitespace (we expect simple usernames)
            if (strpos($new_username, '@') !== false || preg_match('/\s/', $new_username)) {
                $error = 'Usernames must not contain @ or spaces.';
            } else if (strlen($new_username) < 5) {
                $error = 'Username must be at least 5 characters long.';
            } else {
                // Ensure the username is not used by another account (stored in `email` column)
                $check = $conn->prepare('SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1');
                $check->execute([$new_username, $userId]);
                if ($check->rowCount() > 0) {
                    $error = 'That username is already taken by another account.';
                } else {
                    $updates[] = 'email = ?';
                    $params[] = $new_username;
                }
            }
        }

        // Recovery email update (optional)
        if (!empty($recovery_email)) {
            if (!filter_var($recovery_email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please provide a valid recovery email address.';
            } else {
                $updates[] = 'recovery_email = ?';
                $params[] = $recovery_email;
            }
        }

        // Password update (optional)
        if (!empty($new_password) || !empty($confirm_password)) {
            if (strlen($new_password) < 6) {
                $error = 'New password must be at least 6 characters long.';
            } else if ($new_password !== $confirm_password) {
                $error = 'New password and confirmation do not match.';
            } else if (password_verify($new_password, $user['password'])) {
                // New password is the same as the current password
                $error = 'New password must be different from your current password.';
            } else {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $updates[] = 'password = ?';
                $params[] = $hashed;
            }
        }

        if (empty($error)) {
            if (!empty($updates)) {
                $params[] = $userId; // for WHERE
                $sql = 'UPDATE users SET ' . implode(', ', $updates) . ' WHERE id = ?';
                $stmt = $conn->prepare($sql);
                if ($stmt->execute($params)) {
                    $success = 'Credentials updated successfully.';
                    // If email changed, update session
                    if (!empty($new_username) && $new_username !== $user['email']) {
                        $_SESSION['email'] = $new_username;
                    }
                    // Refresh user variable
                    $stmt = $conn->prepare('SELECT id, first_name, last_name, email, password, recovery_email FROM users WHERE id = ? LIMIT 1');
                    $stmt->execute([$userId]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $error = 'Failed to update credentials. Please try again.';
                }
            } else {
                $error = 'No changes detected.';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Credentials</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../assets/icons/worm.png">
    <link rel="stylesheet" href="../index.css">
    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            max-width: 300px;
        }
        .notification.success {
            background-color: #10b981;
            color: white;
        }
        .notification.error {
            background-color: #ef4444;
            color: white;
        }
        .notification.info {
            background-color: #3b82f6;
            color: white;
        }
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 min-h-screen">

<?php include __DIR__ . '/../includes/navBar.php'; ?>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-extrabold mb-8">Update Credentials</h1>

        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 rounded text-red-700 bg-red-100"><?=htmlspecialchars($error)?></div>
        <?php elseif (!empty($success)): ?>
            <div class="mb-4 p-3 rounded text-green-700 bg-green-100"><?=htmlspecialchars($success)?></div>
        <?php endif; ?>

        <form method="post" novalidate>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Username Section (Left Column) -->
                <div class="pb-8 border-b lg:border-b-0 lg:border-r border-gray-200 lg:pr-10">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Username & Email</h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-l-md">
                                    <img src="../assets/icons/worm.png" alt="logo" class="w-5 h-5">
                                </div>
                            </div>
                            <input type="text" value="<?=htmlspecialchars($user['email'])?>" disabled class="w-full pl-14 p-3 border border-gray-300 rounded-md bg-gray-50" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Username (optional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-l-md">
                                    <img src="../assets/icons/worm.png" alt="logo" class="w-5 h-5">
                                </div>
                            </div>
                            <input type="text" name="new_username" placeholder="Enter new username" value="<?=isset($new_username) ? htmlspecialchars($new_username) : ''?>" class="w-full pl-14 p-3 border border-gray-300 rounded-md bg-white" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recovery Email (optional)</label>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-0">
                            <input type="email" id="recoveryEmailInput" placeholder="your@email.com" value="<?=isset($user['recovery_email']) ? htmlspecialchars($user['recovery_email']) : ''?>" class="flex-1 p-3 border border-gray-300 rounded-md bg-white" <?=!empty($user['recovery_email']) ? 'readonly' : ''?> />
                            <?php if (empty($user['recovery_email'])): ?>
                                <button type="button" id="sendVerificationBtn" class="w-full sm:w-auto px-3 py-2 sm:py-3 text-blue-600 hover:text-blue-800 font-semibold text-sm bg-white border border-gray-300 sm:border-l-0 rounded-md sm:rounded-l-none whitespace-nowrap">Send Code</button>
                            <?php else: ?>
                                <div class="w-full sm:w-auto px-3 py-2 sm:py-3 flex items-center justify-between sm:justify-end gap-2 text-sm bg-white border border-gray-300 sm:border-l-0 rounded-md sm:rounded-l-none">
                                    <button type="button" id="editRecoveryEmailBtn" class="text-blue-600 hover:text-blue-800 font-semibold">Edit</button>
                                    <span class="text-gray-400 hidden sm:inline">|</span>
                                    <span class="text-green-600 font-semibold">Verified</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Email Verification Code Input (Hidden by default) -->
                    <div id="verificationCodeSection" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
                        <div class="relative">
                            <input type="text" id="verificationCode" placeholder="Enter 6-digit code" maxlength="6" class="w-full p-3 border border-gray-300 rounded-md bg-white text-center tracking-widest" />
                        </div>
                        <p id="verificationStatus" class="text-sm text-gray-500 mt-2"></p>
                    </div>
                </div>

                <!-- Password Section (Right Column) -->
                <div class="lg:pl-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Password</h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-l-md text-gray-600">
                                    <i class='bx bx-lock-alt'></i>
                                </div>
                            </div>
                            <input type="password" name="new_password" placeholder="At least 6 characters" class="w-full pl-14 p-3 border border-gray-300 rounded-md bg-white" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-l-md text-gray-600">
                                    <i class='bx bx-lock-alt'></i>
                                </div>
                            </div>
                            <input type="password" name="confirm_password" placeholder="Repeat new password" class="w-full pl-14 p-3 border border-gray-300 rounded-md bg-white" />
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password (required)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-l-md text-gray-600">
                                    <i class='bx bx-lock-alt'></i>
                                </div>
                            </div>
                            <input type="password" name="current_password" required placeholder="Your current password" class="w-full pl-14 p-3 border border-gray-300 rounded-md bg-white" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="../public/index.php" class="px-4 py-2 rounded-md bg-gray-100 border border-gray-200 hover:bg-gray-200">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded-md bg-green-500 text-white hover:bg-green-600">Update</button>
            </div>
        </form>
    </div>
</main><?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
// Email Verification Flow
const recoveryEmailInput = document.getElementById('recoveryEmailInput');
const sendVerificationBtn = document.getElementById('sendVerificationBtn');
const editRecoveryEmailBtn = document.getElementById('editRecoveryEmailBtn');
const verificationCodeSection = document.getElementById('verificationCodeSection');
const verificationCodeInput = document.getElementById('verificationCode');
const verificationStatus = document.getElementById('verificationStatus');

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Edit recovery email
if (editRecoveryEmailBtn) {
    editRecoveryEmailBtn.addEventListener('click', function(e) {
        e.preventDefault();
        recoveryEmailInput.readOnly = false;
        recoveryEmailInput.focus();
        recoveryEmailInput.value = '';
        verificationCodeSection.classList.add('hidden');
        if (verificationCodeInput) verificationCodeInput.value = '';
        if (verificationStatus) verificationStatus.textContent = '';
        
        // Replace Edit button with Send Code button
        const parent = editRecoveryEmailBtn.parentElement;
        parent.innerHTML = '<button type="button" id="sendVerificationBtn" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Send Code</button>';
        
        // Re-attach the send verification event
        document.getElementById('sendVerificationBtn').addEventListener('click', sendCode);
    });
}

// Send verification code
function sendCode(e) {
    if (e) e.preventDefault();
    
    const email = recoveryEmailInput.value.trim();
    
    if (!email) {
        showNotification('Please enter an email address', 'error');
        return;
    }
    
    if (!validateEmail(email)) {
        showNotification('Please enter a valid email address', 'error');
        return;
    }
    
    const btn = document.getElementById('sendVerificationBtn');
    btn.disabled = true;
    btn.textContent = 'Sending...';
    
    try {
        fetch('../functions/send_verification_email.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=send_verification&email=' + encodeURIComponent(email)
        }).then(response => response.json()).then(data => {
            if (data.success) {
                showNotification('Verification code sent to ' + email, 'success');
                verificationCodeSection.classList.remove('hidden');
                verificationCodeInput.focus();
                verificationStatus.textContent = 'Code expires in 15 minutes';
                btn.textContent = 'Code Sent';
                
                // Auto-verify when user finishes typing
                verificationCodeInput.addEventListener('input', autoVerifyCode);
            } else {
                showNotification('Error: ' + data.message, 'error');
                btn.disabled = false;
                btn.textContent = 'Send Code';
            }
        }).catch(error => {
            showNotification('Error sending verification code: ' + error.message, 'error');
            btn.disabled = false;
            btn.textContent = 'Send Code';
        });
    } catch (error) {
        showNotification('Error sending verification code: ' + error.message, 'error');
        btn.disabled = false;
        btn.textContent = 'Send Code';
    }
}

// Attach send code event
if (sendVerificationBtn) {
    sendVerificationBtn.addEventListener('click', sendCode);
}

// Auto-verify when user enters 6 digits
function autoVerifyCode() {
    if (verificationCodeInput.value.length === 6) {
        verifyCode();
    }
}

// Verify the code
async function verifyCode() {
    const code = verificationCodeInput.value.trim();
    
    if (code.length !== 6 || !/^\d{6}$/.test(code)) {
        verificationStatus.textContent = 'Please enter a valid 6-digit code';
        verificationStatus.className = 'text-sm text-red-600 mt-2';
        return;
    }
    
    verificationStatus.textContent = 'Verifying...';
    verificationStatus.className = 'text-sm text-blue-600 mt-2';
    
    try {
        const response = await fetch('../functions/verify_email_code.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'code=' + encodeURIComponent(code)
        });
        
        const data = await response.json();
        
        if (data.success) {
            verificationStatus.textContent = data.message;
            verificationStatus.className = 'text-sm text-green-600 mt-2 font-semibold';
            if (verificationCodeInput) verificationCodeInput.disabled = true;
            const btn = document.getElementById('sendVerificationBtn');
            if (btn) btn.disabled = true;
            recoveryEmailInput.readOnly = true;
        } else {
            verificationStatus.textContent = data.message;
            verificationStatus.className = 'text-sm text-red-600 mt-2';
            if (verificationCodeInput) {
                verificationCodeInput.value = '';
                verificationCodeInput.focus();
            }
        }
    } catch (error) {
        verificationStatus.textContent = 'Error: ' + error.message;
        verificationStatus.className = 'text-sm text-red-600 mt-2';
    }
}

// Email validation helper
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Allow Enter key to verify code
verificationCodeInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && this.value.length === 6) {
        verifyCode();
    }
});
</script>
</body>
</html>
