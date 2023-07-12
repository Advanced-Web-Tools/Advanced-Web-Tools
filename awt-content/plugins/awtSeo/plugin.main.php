<?php

use admin\navbar;
$seo = new navbar;

if(function_exists('navbarLoader')) {
    $location = HOSTNAME.'awt-content/plugins/awtSeo/data/icons/';

    $seo->addItem(array('icon' => $location.'bolt-solid.svg', 'name'=>'SEO', 'link' => '?page=awtSEO', 'permission' => 1));

    array_push($navbar, $seo);
}

$pluginPages['awtSEO'] = PLUGINS.'awtSEO'.DIRECTORY_SEPARATOR.'plugin.page.php';
