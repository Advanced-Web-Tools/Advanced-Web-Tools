<?php
define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, profiler};
use mail\mail;

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

$profiler = new profiler;

if (isset($_POST['fetch'])) {
    $mail = new mail("", "", "");
    if($_POST['fetch'] != 2) die(json_encode($mail->fetchMail((int) $_POST['fetch'], true)));
    die(json_encode($mail->fetchMailInbox()));
}

if (isset($_POST['send'])) {
    $mail = new mail($profiler->email, $_POST['recipient'], $_POST['subject'], $_POST['content']);
    $status = $mail->sendMessage($profiler->firstname . " " . $profiler->lastname);
    die(json_encode($status));
}

if (isset($_POST['test'])) {
    $mail = new mail($profiler->email, "", "");
    $status = $mail->sendTestMessage();
    die(json_encode($status));
}

if(isset($_POST['load'])) {
    $mail = new mail("", "", "");
    die(json_encode($mail->getMessage((int) $_POST['load'], true)));
}

die(json_encode("Invalid request"));