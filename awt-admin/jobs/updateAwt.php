<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, profiler, admin};
use store\store;

$check = new authentication;
$profiler = new profiler;
$admin = new admin;

if($profiler->checkPermissions(0)) {
    $update = new store("getLatestAWTVersion", "Advanced Web Tools", "AWT");

    $update->updateAWTVersion();
    header("Location: ". HOSTNAME ."/awt-admin/index.php?page=settings&status=update_succesfull");
    exit();
}

header("Location: ". HOSTNAME ."/awt-admin/index.php?page=settings&status=not_allowed");
exit();