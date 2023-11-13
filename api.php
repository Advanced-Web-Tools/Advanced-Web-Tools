<?php

use media\albums;
use media\media;

define("API", 1);

if (!file_exists('./awt-config.php')) {
   echo "ERROR";
   exit();
}

require_once './awt-config.php';


$api_executors = array();


include_once JOBS . 'awt-domainBuilder.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';


addToApiExecution("media", $class = new media);
addToApiExecution("albums", $class = new albums);


if (isset($_POST["request"]) && key_exists($_POST["request"], $api_executors)) {

   $api_executors[$_POST["request"]]->Api();

   exit();
}

die("Error invalid request");