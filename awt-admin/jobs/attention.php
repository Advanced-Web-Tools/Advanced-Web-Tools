<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\authentication;
use notifications\attention;


$check = new authentication;


if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

$attention = new attention("dashboard", "check");

if(isset($_POST['getAttention'])) {
    die(json_encode($attention->getUnresolved()));
}

if(isset($_POST['setAsSolved'])) {
    die($attention->setAsSolved($_POST['setAsSolved']));
}

die("Invalid request");