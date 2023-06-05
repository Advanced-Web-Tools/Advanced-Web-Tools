<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\authentication;
use settings\settings;

$check = new authentication;


if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

$settings = new settings;

if(isset($_GET['name'])) {
    if(!isset($_POST['value'])) $_POST['value'] = 'false';
    if($_POST['value'] == 'on') $_POST['value'] = 'true';
    $settings->setSetting($_GET['name'], $_POST['value'], $_POST['applied_when']);
    header("Location: ../?page=Settings");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode($settings->allSettings);
    exit();
}
