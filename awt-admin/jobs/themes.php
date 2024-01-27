<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, profiler};
use themes\themes;
use content\themeInstaller;

$check = new authentication;
$profiler = new profiler;

$themes = new themes();
$installer = new themeInstaller();

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['get_themes']))
{
    echo json_encode($themes->getThemes());
}

if(isset($_POST['get_active_theme'])) {
    echo json_encode($themes->getActiveTheme());
}

if(isset($_POST['enable_theme']))
{   
    if($themes->enableTheme($_POST['enable_theme'], $profiler)) {
        echo json_encode("OK");
    } else {
        echo json_encode("NOT OK");
    }
    
}

if (!$profiler->checkPermissions(0)) {
    header("Location: ../?page=Themes&status=permissionDenied");
    exit();
}

if(isset($_POST['delete_theme'])) {
    $response = $installer->removeTheme($_POST['name'], $_POST['delete_theme']);
    echo json_encode($response);
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