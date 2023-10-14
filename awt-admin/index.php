<?php

define('DASHBOARD', 1);

include '../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-navbarLoader.php';
include_once JOBS . 'awt-domainBuilder.php';
include_once FUNCTIONS . 'awt-navbar.fun.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';


use admin\authentication;
use admin\profiler;

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

use paging\paging;

if (!isset($_GET['page'])) {
    header('Location: ./?page=Dashboard');
    exit();
}


$profiler = new profiler;
$profile = $profiler->getProfile();


$paging = new paging($pluginPages);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/navbar.css">
    <link href="../awt-src/vendor/fontawesome-free-6.4.2-web/css/all.css" rel="stylesheet">
</head>

<body>
    <section class="main-section">
        <nav class="main-navbar">
            <div class="profiler">
                <h3>Welcome <?php echo $profile['fname'] . " " . $profile['lname']; ?></h3>
                <h5>Username: <?php echo $profile['name']; ?></h5>
            </div>
            <?php navbarLoader($navbar); ?>
        </nav>
        <section class="page">
            <?php $paging->getPage(true, "paging"); ?>
        </section>
    </section>
</body>

</html>