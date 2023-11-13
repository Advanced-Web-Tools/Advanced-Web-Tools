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

$paging = new paging(array());

if (isset($_POST['htmlContent']) && isset($_POST['name'])) {
    if (isset($_POST['pageStatus'])) {
        $paging->uploadPage($_POST['name'], $_POST['htmlContent'], $_POST['pageStatus']);
        echo "upload";
    } else {
        $paging->uploadPage($_POST['name'], $_POST['htmlContent']);
        echo "upload";
    }
}
echo $_POST['name'];
