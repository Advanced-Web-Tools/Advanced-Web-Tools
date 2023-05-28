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
    <title><?php echo WEB_NAME; ?> | Login</title>
</head>
<body>
    <section class="login">
        <form action="./jobs/signInOut.php" method="post">
            <input type="username" name="username" id="password">
            <input type="password" name="password" id="password">
            <button type="submit" name="login">Login</button>
        </form>
    </section>
</body>
</html>