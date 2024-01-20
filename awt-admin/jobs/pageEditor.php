<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';



use paging\editor;
use admin\{authentication, profiler};

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

$paging = new editor(array());

if(isset($_POST['getBlocks'])) die(json_encode($pluginBlocks));

if(isset($_POST['getBlock'])) die(getBlock($_POST['getBlock']));

if(isset($_POST['name']) && isset($_POST['htmlContent']) && isset($_POST['pageStatus'])) $paging->uploadPage($_POST['name'], $_POST['htmlContent'], $_POST['pageStatus']);