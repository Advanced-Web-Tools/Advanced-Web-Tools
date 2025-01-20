<?php

use setting\Settings;
use setting\Config;
$settings = new Settings();

$settings->fetchSettings()->initSettings();

if (defined("SETT_AWT_WEBSITE_NAME")) {
    $val = Config::getConfig("AWT", "website name")->getValue();
    define("WEB_NAME", $val);
} else {
    define("WEB_NAME", "AWT");
}