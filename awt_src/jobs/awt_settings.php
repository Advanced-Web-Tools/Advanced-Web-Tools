<?php

use setting\Settings;

$settings = new Settings();

$settings->fetchSettings()->initSettings();

if (defined("SETT_AWT_WEBSITE_NAME")) {
    define("WEB_NAME", SETT_AWT_WEBSITE_NAME->getValue());
} else {
    define("WEB_NAME", "AWT");
}


//if (WHITELIST == 'true') {
//    str_contains(WHITELIST_LIST, $_SERVER['REMOTE_ADDR']) == true or die("This website is whitelisted. And your IP is not on the list! {$_SERVER['REMOTE_ADDR']}");
//}