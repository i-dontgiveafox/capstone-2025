<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h3>Login</h3>
    <?php if (isset($_GET['error'])) { ?>
        <b style="color: #f00"><?=$_GET['error']?></b>
    <?php } ?>
    <form action="../functions/login-func.php" method="POST">
        <label>Email</label><br>
        <input type="text" name="email"><br>
        <label>Password</label><br>
        <input type="text" name="password"><br>
        <button type="submit">Login</button>
        <a href="signup.php">Sign Up</a>
    </form>
</body>
</html>