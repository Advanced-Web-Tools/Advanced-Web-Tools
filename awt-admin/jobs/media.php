<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, profiler};

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploadedFiles']) && $profiler->checkPermissions(2)) {
    $uploadedFiles = $_FILES['uploadedFiles'];

    $mediaHandler = new media\media();
    
    try {
        $uploadedFileNames = $mediaHandler->uploadFiles($uploadedFiles);
    } catch (\Exception $e) {
        json_encode('Error: ' . $e->getMessage());
    }
}

exit();
