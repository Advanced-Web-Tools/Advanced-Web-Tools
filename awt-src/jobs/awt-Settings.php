<?php

use settings\settings;

$settings = new settings;

error_reporting($settings->getSettingsValue('PHP Error reporting'));
ini_set("display_errors", $settings->getSettingsValue('PHP Error reporting'));


define('WHITELIST', $settings->getSettingsValue('whitelist'));
define('WHITELIST_LIST', $settings->getSettingsValue('whitelist_list'));
define('USE_PLUGINS', $settings->getSettingsValue('use_plugins'));
define('HOSTNAME_PATH', $settings->getSettingsValue('hostname_path'));

if(WHITELIST == 'true'){
    str_contains(WHITELIST_LIST, $_SERVER['REMOTE_ADDR']) == true or die("This webiste is whitelisted. And your IP is not on the list! {$_SERVER['REMOTE_ADDR']}");
}