<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-themesLoader.php';

use paging\paging;
use admin\{authentication, profiler};

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

$pages = new paging(array());
if(isset($_POST['getPages'])) echo json_encode($pages->getAllPages());
if(isset($_POST['getAllPages'])) echo json_encode($pages->getEveryPage());
if(isset($_POST['createEmpty']) && isset($_POST['name'])) die(json_encode($pages->createEmptyPage($_POST['name'])));
if(isset($_POST['deletePage']) && isset($_POST['id']) && $profiler->checkPermissions(0)) echo json_encode($pages->deletePage($_POST['id']));