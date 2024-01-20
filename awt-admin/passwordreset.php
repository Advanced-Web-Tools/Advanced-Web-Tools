<?php

define('DASHBOARD', 1);

include '../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-navbarLoader.php';
include_once JOBS . 'awt-domainBuilder.php';
include_once FUNCTIONS . 'awt-navbar.fun.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';


use admin\{authentication, reset};
use admin\profiler;

$check = new authentication;

$reset = new reset;

if($check->checkAuthentication()) {
    header("Location: ./index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/passwordReset.css">
    <link href="../awt-src/vendor/fontawesome-free-6.5-web/css/all.css" rel="stylesheet">
    <title><?php echo WEB_NAME; ?> | Forgot Password</title>
</head>
<body>

    <?php if(!isset($_GET['code'])): ?>

    <section class="forgot section">
        <div class="header">
            <h2>Reset password</h2>
        </div>
        <form action="./jobs/passwordReset.php" method="post">
            <input type="email" name="email" class="input" placeholder="Email">
            <button type="submit" class="button" name="get-code">Get code</button>
        </form>
    </section>

    <?php elseif($reset->checkPasswordResetCode((int) $_GET['code'])): ?>
    
        <section class="forgot section">
        <div class="header">
            <h2>Enter new password</h2>
        </div>
        <form action="./jobs/passwordReset.php?code=<?php echo $_GET['code'] ?>" method="post">
            <input type="password" name="password" class="input" placeholder="New password">
            <button type="submit" class="button" name="reset-password">Reset password</button>
        </form>
        </section>
    
    <?php else: ?>

        <section class="forgot section">
        <div class="header">
            <h2>Invalid request</h2>
        </div>
        <p>Looking for something?</p>
        </section>

    <?php endif; ?>
</body>
</html>