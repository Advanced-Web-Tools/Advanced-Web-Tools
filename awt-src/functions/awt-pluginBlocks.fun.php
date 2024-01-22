<?php
use blocks\{BlockCollection, block, BlockOptions};

function createBlockCollection(string $collection, BlockCollection $blockCollection)
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
    $pluginBlocks[$collection] = $blockCollection;
    $loadedBlocks[] = $blockCollection->blocks;
}

function getBlockFromCollection(string $collection, string $name)
{
    global $pluginBlocks;
    global $loadedBlocks;
    return $pluginBlocks[$collection]->blocks[$name]->getBlock();
}


function getCollectionInfo(string $name)
{
    global $pluginBlocks;
    return $pluginBlocks[$name]->getInfo();
}

function getAllBlocksFromCollection(string $name)
{
    global $pluginBlocks;
    return $pluginBlocks[$name]->getAllBlocks();
}

function findBlock(string $name)
{
    global $loadedBlocks;

    foreach ($loadedBlocks as $key => $value) {
        foreach ($value as $block) {
            if ($block->name == $name) {
                return $block;
            }
        }
    }

    return $loadedBlocks[0][array_key_first($loadedBlocks[0])];
}

function getBlock(string $name)
{
    global $loadedBlocks;
    return findBlock($name)->getBlock();
}

function getBlockInfo(string $name)
{   
    global $loadedBlocks;
    return findBlock($name)->getInfo();
}


function addBlockOption(BlockOptions $options) {
    global $blockOptions;
    array_push($blockOptions, $options);
}

function getBlockOptions() {
    global $blockOptions;
    foreach ($blockOptions as $key => $option) {
        $option->loadOption();
    }
}
