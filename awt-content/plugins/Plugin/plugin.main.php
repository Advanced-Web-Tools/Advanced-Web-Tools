<?php
use admin\navbar;

if(defined('DASHBOARD')) { 

    $name = "Plugin";  ///This is plugins name that can be used to quickly create links and paths to your resources, its not mandatory
    

    $nav = new navbar;

    $location = HOSTNAME . 'awt-content/plugins/'. $name .'/data/icons/';
    
    $nav->addItem(array('icon' => $location . 'espaol-svgrepo-com.svg', 'name' => 'Plugin', 'link' => HOSTNAME . 'awt-admin/?page=Tutorial', 'permission' => 2));
    
    array_push($navbar, $nav);

    $pluginPages['Tutorial'] = PLUGINS.$name.DIRECTORY_SEPARATOR.'plugin.page.php';
}