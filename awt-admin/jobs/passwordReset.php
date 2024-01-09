<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, reset};

if(isset($_POST['get-code'])) {
    $email = $_POST['email'];
    $reset = new reset($email);

    if(!$reset->generateResetLink()){
        header("Location: ../passwordreset.php?no_accounts");
        exit();
    } else {
        header("Location: ../passwordreset.php?sent");
        exit();
    }
}

if(isset($_POST['reset-password'])) {
    $password = $_POST['password'];
    $reset = new reset();

    if(!$reset->forgotPasswordRestart($_GET['code'], $password)){
        header("Location: ../passwordreset.php?password_not_changed");
        exit();
    } else {
        header("Location: ../login.php?password_changed");
        exit();
    }
}