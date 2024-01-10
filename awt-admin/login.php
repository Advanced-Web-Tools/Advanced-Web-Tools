<?php
    
    define('DASHBOARD', 1);

    include '../awt-config.php';
    include_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-autoLoader.php';
    include_once JOBS.'awt-domainBuilder.php';
    include_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-pluginLoader.php';

    use admin\authentication;
    $check = new authentication;
    if($check->checkAuthentication()) {
        header("Location: ./index.php?status=logedIn");
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
    <link rel="stylesheet" href="./css/login.css">
    <link href="../awt-src/vendor/fontawesome-free-6.4.2-web/css/all.css" rel="stylesheet">
    <title><?php echo WEB_NAME; ?> | Login</title>
</head>
<body>
    <section class="login">
        <div class="form-wrapper">
            <form action="./jobs/signInOut.php" method="post">
                <div class="form-header">
                    <h1>Welcome back!</h1>
                    <p>Please login to continue.</p>
                </div>
                <input type="username" name="username" id="username" placeholder="Username or email">
                <input type="password" name="password" id="password" placeholder="Password">
                <button type="submit" name="login"><i class="fa-solid fa-arrow-right"></i></button>
                <div class="lost">
                    <a href="./passwordreset.php">Forgot password <i class="fa-solid fa-key"></i></a>
                    <p>Are you lost?</p>
                    <hr>
                    <a href="../">Go to the visitor area  <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </form>
        </div>
    </section>
</body>
</html>