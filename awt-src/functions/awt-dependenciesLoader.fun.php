<?php

function addDependencie($priority, $name, $path, $version = '')
{
    global $dependencies;
    $dependencies[$priority][] = array('name' => $name, 'path' => $path, 'version' => $version);
}

function dependenciesLoader()
{   
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
    
    $highPriority = array();
    $mediumPriority = array();
    $lowPriority = array();
    
    if(isset($dependencies['high'])) {
        $highPriority = $dependencies['high'];
    }

    if(isset($dependencies['medium'])) {
        $mediumPriority = $dependencies['medium'];
    }

    if(isset($dependencies['low'])) {
        $lowPriority = $dependencies['low'];
    }

    foreach($highPriority as $dependencie) {
        if (!file_exists($dependencie['path'])) {
            echo "Failed to load dependencie ". $dependencie['name'] ." on ". $dependencie['path']. "<br>";
            return false;
        }

        include_once $dependencie['path'];
    }

    foreach($mediumPriority as $dependencie) {
        if (!file_exists($dependencie['path'])) {
            echo "Failed to load dependencie ". $dependencie['name'] ." on ". $dependencie['path']. "<br>";
            return false;
        }

        include_once $dependencie['path'];
    }

    foreach($lowPriority as $dependencie) {

        if (!file_exists($dependencie['path'])) {
            echo "Failed to load dependencie ". $dependencie['name'] ." on ". $dependencie['path']. "<br>";
            return false;
        }

        include_once $dependencie['path'];
    }
    
}