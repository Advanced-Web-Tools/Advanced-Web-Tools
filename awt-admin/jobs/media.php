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
        header("Location: ../?page=Media");
        exit();
    } catch (\Exception $e) {
        json_encode('Error: ' . $e->getMessage());
        header("Location: ../?page=Media");
        exit();
    }
}

if(isset($_POST["create_album"])) {
    $album = new media\albums();
    $album->createAlbum($_POST["create_album"]);
    exit();
}

if(isset($_POST["fetch_albums"])) {
    $album = new media\albums();
    echo json_encode($album->getAllAlbums());
    exit();
}

if(isset($_POST["delete_album"])) {
    $album = new media\albums();
    $mediaHandler = new media\media();
    $mediaHandler->globallyRemoveFromAlbum($_POST["delete_album"]);
    echo json_encode($album->deleteAlbum($_POST["delete_album"]));
    exit();
}

if(isset($_POST["get_media"])) {
    $mediaHandler = new media\media();
    echo json_encode($mediaHandler->getMedia());
    exit();
}

if(isset($_POST["delete_media"])) {
    $mediaHandler = new media\media();

    $ids = json_decode($_POST["delete_media"], true);
    foreach ($ids as $key => $value) {
        $mediaHandler->deleteMedia($value);
    }

    exit();
}

if(isset($_POST["move_to_album"]) && isset($_POST["media"])) {
    $mediaHandler = new media\media();

    $ids = json_decode($_POST["media"], true);
    foreach ($ids as $key => $value) {
        $mediaHandler->moveToAlbum($value, $_POST["move_to_album"]);
    }

    exit();
}

if(isset($_POST["get_media_from_album"])) {
    $mediaHandler = new media\media();
    echo json_encode($mediaHandler->getMediaFromAlbum($_POST["get_media_from_album"]));
    exit();
}



exit();
