<?php 
session_start();
if (isset($_SESSION['email']) && 
    isset($_SESSION['user_id'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

</head>

<body class="bg-gradient-to-t from-[#CCEBD5] to-[#D7E0DF] h-screen">
    <?php include '../includes/navBar.php'; ?>

    <h2>Welcome, <?php echo $_SESSION['first_name']; ?>!</h2>
    <h3>Email: <i><?php echo $_SESSION['email']; ?>!</i></h3>
    <!--<div class="container mx-auto mt-10 p-4 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Welcome to MySite</h1>
        <p class="mb-4">This is a sample page using Tailwind CSS for styling.</p>
    </div>-->
    <a href="logout.php">Logout</a>
    
</body>

</html>

<?php } else {
    $errorM = "Login First!";
    header("Location: ../public/login.php?error=$errorM");

} ?>