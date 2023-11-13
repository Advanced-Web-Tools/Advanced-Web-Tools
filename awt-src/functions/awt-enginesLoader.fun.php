<?php

function addEngine(string $name, string $path, string $version, string $runtime) {
    global $engines;
    $engines[] = array('name' => $name, 'path' => $path, 'version' => $version, 'runtime' => $runtime);
}

function enginesLoader(string $runtime) {

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
            continue;
        }

        if($engine['runtime'] == $runtime)  include_once $engine['path'];
    }
    return true;

}