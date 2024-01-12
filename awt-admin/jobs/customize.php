<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-themesLoader.php';

use admin\{authentication, profiler};
use themes\themes;

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['get_built_in_pages'])) die(json_encode($builtInPages));
