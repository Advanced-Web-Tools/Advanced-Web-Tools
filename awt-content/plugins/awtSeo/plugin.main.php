<?php

use admin\navbar;
$metrics = new navbar;

if(function_exists('navbarLoader')) {
    $location = HOSTNAME.'awt-content/plugins/awtSeo/data/icons/';

    $metrics->addItem(array('icon' => $location.'bolt-solid.svg', 'name'=>'SEO', 'link' => '?page=awtSEO', 'permission' => 1));

    array_push($navbar, $metrics);
}

$pluginPages['awtSEO'] = PLUGINS.'awtSEO'.DIRECTORY_SEPARATOR.'plugin.page.php';
