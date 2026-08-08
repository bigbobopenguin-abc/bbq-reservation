<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BBQ Hub</title>
    <link rel="stylesheet" href="css/css_reset.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <?php include_once("header.php") ?>

    <section id="main">
        <div class="login-card">
            <h1>Login</h1>

            <?php if (isset($_GET['registered'])): ?>
                <div class="success-box">Account created successfully! Please log in.</div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-box">Your username or password is incorrect.</div>
            <?php endif; ?>

            <form action="login_process.php" method="post">
                <div class="form-group">
                    <label for="uid">Username</label>
                    <input type="text" id="uid" name="uid" required>
                </div>
                <div class="form-group">
                    <label for="pw">Password</label>
                    <input type="password" id="pw" name="pw" required>
                </div>
                <button class="btn btn-accent btn-block">Login</button>
            </form>

            <p class="login-register-link">Don't have an account? <a href="register.php">Create one here</a></p>
        </div>
    </section>

    <?php include_once("footer.php") ?>
</body>

</html>