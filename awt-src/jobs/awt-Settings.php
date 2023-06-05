<?php

use settings\settings;

$settings = new settings;

define('WHITELIST', $settings->getSettingsValue('whitelist'));
define('WHITELIST_LIST', $settings->getSettingsValue('whitelist_list'));
define('USE_PLUGINS', $settings->getSettingsValue('use_plugins'));
define('HOSTNAME_PATH', $settings->getSettingsValue('hostname_path'));

if(WHITELIST == 'true'){
    str_contains(WHITELIST_LIST, $_SERVER['REMOTE_ADDR']) == true or die("Site whitelisted");
}