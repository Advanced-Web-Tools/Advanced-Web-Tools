<?php

function createBlockCollection(string $collection)
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
    global $pluginBlocks;
    global $loadedBlocks;
    $pluginBlocks[$collection] = array();
}

function addBlock(array $block, string $collection)
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
    global $pluginBlocks;
    global $loadedBlocks;
    array_push($pluginBlocks[$collection], $block);
    $loadedBlocks[] = $block['name'];
}

function checkForCollection(string $collection)
{
    global $pluginBlocks;
    global $loadedBlocks;

    if(array_key_exists($collection, $pluginBlocks)) return true;

    return false;
}

function checkBlock(string $name) {
    global $pluginBlocks;
    global $loadedBlocks;
    foreach($pluginBlocks as $collection)
    {
        foreach($collection as $blocks => $value) {
            if($value["name"] == $name) return $value;
        }
        
    }
    return false;
}

function loadBlock(string $name)
{   
    $test = checkBlock($name);
    if($test !== false) {
        return $test;
    } else {
        die("ERROR loading block $name");
    }
}

function getBlockPath(string $name)
{
    $test = checkBlock($name);
    if($test !== false) return $test['path'];
    die("ERROR loading block $name");
}
function getCollection(string $collection)
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
    global $pluginBlocks;
    global $loadedBlocks;
    if(array_key_exists($collection, $pluginBlocks)) return $pluginBlocks[$collection];
    die("Error collection of block: $collection does not exist");
}


function returnCollections()
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
    global $pluginBlocks;
    global $loadedBlocks;
    return $pluginBlocks;
}