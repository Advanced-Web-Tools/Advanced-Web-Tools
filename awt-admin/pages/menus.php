<?php

defined('DASHBOARD') or  die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\authentication;

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

?>

<link rel="stylesheet" href="./css/menus.css">
<script src="./javascript/menus/menus.js"></script>

<div class="menu-list shadow">

</div>

<div class="menu-options shadow">

</div>
