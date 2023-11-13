<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");



?>
<link rel="stylesheet" href="./css/dashboard.css">
<div class="widgets">
    <?php loadAllWidgets(); ?>
</div>