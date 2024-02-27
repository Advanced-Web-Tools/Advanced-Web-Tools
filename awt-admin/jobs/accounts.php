<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, profiler, admin};
use mail\mail;

$check = new authentication;
$profiler = new profiler;
$admin = new admin;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['get_accounts'])) {
    echo json_encode($admin->getAccountList());
}

if (isset($_POST['create_account'])) {

    echo json_encode($admin->createAccount(
        $_POST["email"],
        $_POST["username"],
        $_POST["firstname"],
        $_POST["lastname"],
        $_POST["password"],
        $_POST["permission"]
    ));
}

if(isset($_POST['delete_account'])) {
    echo json_encode($admin->deleteAccount($_POST['delete_account']));
}

if(isset($_POST['send_email'])) {
    $mail = new mail($profiler->email, $_POST["receiver"], $_POST["subject"], $_POST["content"]);
    echo json_encode($mail->sendMessage($profiler->firstname . " " . $profiler->lastname));
}

if(isset($_POST['edit_info'])) {

    if(trim($_POST["password"]) == "") $_POST["password"] = null;

    die(json_encode($admin->updateInfo($profiler->id, $_POST["firstname"], $_POST["lastname"], $_POST["email"], $_POST["password"])));
}

