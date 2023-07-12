<?php

function addEngine($name, $path, $version, $runtime) {
    global $engines;
    $engines[] = array('name' => $name, 'path' => $path, 'version' => $version, 'runtime' => $runtime);
}

function enginesLoader($runtime) {
    global $dependencies;
    global $plugins;
    global $aio;
    global $settings;
    global $pluginPages;
    global $dashboardWidgets;
    global $menu;
    global $navbar;
    global $engines;
    global $widgets;
    global $loadedPlugins;
    global $pluginBlocks;
    global $loadedBlocks;
    foreach ($engines as $engine) {
        if(!file_exists($engine['path']) && $engine['runtime'] == $runtime) {
            echo 'Faled to load engine '. $engine['name'] . ' at '. $engine['path'];
            return false;
        }
        include $engine['path'];
    }
    return true;

}