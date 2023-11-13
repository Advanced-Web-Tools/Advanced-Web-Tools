<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-autoLoader.php';
include_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-pluginLoader.php';
include_once JOBS.'awt-domainBuilder.php';

use admin\{authentication, admin};

$check = new authentication;

$admin = new admin;

if (isset($_GET['logout'])) {
    $admin->signOut();
    header("Location: ../login.php?status=logedOut");
    exit();
}

if($check->checkAuthentication()) {
    header("Location: ../index.php?status=logedIn");
    exit();
}



if (!isset($_POST['login']) || !isset($_POST['username']) || !isset($_POST['password'])) {
    header("Location: ../login.php?status=allfieldsrequired");
    exit();
}

$status = $check->authenticateUser($_POST['username'], $_POST['password']);
if($status == true) {
    header("Location: ../index.php?status=logedIn&page=Dashboard");
    exit();
} else {
    header("Location: ../login.php?status=authenticationFailed");
    exit();
}