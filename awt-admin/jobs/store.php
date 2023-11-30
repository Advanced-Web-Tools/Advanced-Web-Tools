<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, profiler};
use store\store;

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}


if(isset($_POST["search"])) {

    $search = $_POST["search"];

    $type = "%";

    if(isset($_POST["type"])) $type = $_POST["type"];

    $store = new Store("searchForPackage", $search, $type);

    die(json_encode($store->searchPackage()));

}


if(isset($_POST["installPlugin"]) && $profiler->checkPermissions(0)) {
    $store = new Store();
    die(json_encode($store->installPlugin($_POST["installPlugin"])));
}


if(isset($_POST["installTheme"]) && $profiler->checkPermissions(0)) {

    $store = new Store();

    die(json_encode($store->installPlugin($_POST["installPlugin"])));

}