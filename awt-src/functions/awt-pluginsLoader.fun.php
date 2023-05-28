<?php

function loadAllPlugins()
{   

    global $plugins;
    global $dependencies;
    global $aio;
    global $settings;
    global $pluginPages;
    global $dashboardWidgets;
    global $menu;
    global $navbar;
    global $engines;
    global $widgets;
    global $loadedPlugins;

    foreach ($plugins as $plugin) {
        if ($plugin['status'] === 1) {
            if (file_exists(PLUGINS . $plugin['name'] . DIRECTORY_SEPARATOR . 'plugin.main.php')) {
                include_once PLUGINS . $plugin['name'] . DIRECTORY_SEPARATOR . 'plugin.main.php';
                $loadedPlugins[] = $plugin;
            }
        }
    }
}

function checkForPlugin($pluginName, $version = '', $file = '', $functionCall = '')
{
    global $loadedPlugins;

    foreach($loadedPlugins as $plugin) {
        if($version == '' && $plugin['name'] == $pluginName) {
            if($file != '') include_once $file;
            if($functionCall != '') $functionCall();
            return true;
        }

        if($version == $plugin['version'] && $plugin['name'] == $pluginName) {
            if($file != '') include_once $file;
            if($functionCall != '') $functionCall();
            return true;
        }
    }

}