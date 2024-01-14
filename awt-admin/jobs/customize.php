<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-themesLoader.php';

use admin\{authentication, profiler};
use themes\{themes, settings};

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

$theme = new themes;

if(isset($_POST['get_built_in_pages'])) die(json_encode($builtInPages));

if(isset($_POST['fetch_custom'])){ 
    $theme->getActiveTheme();
    die(json_encode($theme->getAllCustomizedPages($theme->activeTheme['id'])));
}

if(isset($_POST["revert_changes"])) {
    $theme->getActiveTheme();
    die(json_encode($theme->revertChanges($theme->activeTheme['id'])));
}

if(isset($_POST["get_settings"]))
{
    $settings = new settings();
    $settings->getSettings();
    die(json_encode($settings->settings));
}

if(isset($_POST['change_setting']))
{
    $settings = new settings();
    $settings->getSettings();
    $settings->changeSetting($_POST['change_setting'], $_POST['value']);
}

if(isset($_POST['revert_setting']))
{
    $settings = new settings();
    $settings->revertToOriginal($_POST['revert_setting']);
}