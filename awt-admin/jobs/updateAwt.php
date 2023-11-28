<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use store\store;

use admin\{authentication, profiler};

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST["versionCompare"])) {
    $update = new store("getLatestAWTVersion", "Advanced Web Tools", "AWT");
    die(json_encode($update->checkAWTVersion()));
}

if($profiler->checkPermissions(0) && isset($_POST["updateAwt"])) {
    $update = new store("getLatestAWTVersion", "Advanced Web Tools", "AWT");

    $update->updateAWTVersion();
    die("AWT was updated!");
}

die("Error has occured! Check if you are an administrator!");