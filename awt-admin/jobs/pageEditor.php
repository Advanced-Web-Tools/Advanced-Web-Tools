<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';



use paging\paging;
use admin\{authentication, profiler};

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['getBlocks'])) echo json_encode($loadedBlocks);

if(isset($_POST['getBlock'])) include_once getBlockPath($_POST['getBlock']);