<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, profiler};
use plugins\plugins;
use content\pluginInstaller;

$check = new authentication;
$profiler = new profiler;
$plugins = new plugins;
$installer = new pluginInstaller;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['getList'])) {
    $response = $plugins->getPlugins();
    echo json_encode($response);
    exit();
}

if (!$profiler->checkPermissions(0)) {
    header("Location: ../?page=Plugins&status=permissionDenied");
    exit();
}


if (isset($_POST['installer'])) {
    $response = $installer->packageExtractor();
    echo json_encode($response);
    exit();
}

if (isset($_POST['installerAction'])) {
    $action = explode('=', $_POST['installerAction']);
    $response = $installer->installerAction($action[0], $action[1], $action[2]);
    echo json_encode($response);
    exit();
}

if (isset($_POST['uninstall'])) {
    $uninstall = explode('=', $_POST['uninstall']);
    $status = $installer->removePlugin($uninstall[0], $uninstall[1]);
    header("Location: ../?page=Plugins&status=" . $status);
    exit();
}

if (!isset($_GET['id']) || !isset($_POST['action'])) {
    header("Location: ../?page=Plugins&status=notSpecified");
    exit();
}

if (str_contains($_POST['action'], 'authorize=')) {
    $action = explode('=', $_POST['action']);
    $status = $plugins->authorizeDatabase($action[0], $action[1], $_GET['name']);
    header("Location: ../?page=Plugins&status=" . $status);
    exit();
}

$plugins->changeStatus($_GET['id'], $_POST['action']);

header("Location: ../?page=Plugins&status=success");
exit();
