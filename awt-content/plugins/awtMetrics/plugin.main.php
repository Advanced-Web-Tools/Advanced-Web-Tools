<?php

use admin\navbar;
$metrics = new navbar;
$name = 'awtMetrics';
$version = "0.0.1";
$enginePath = PLUGINS.'awtMetrics'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'awtMetrics.php';
$dependenciesPath = PLUGINS.'awtMetrics'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'awtMetrics.class.php';

addDependencie('low', $name, $dependenciesPath, $version);

addEngine($name, $enginePath, $version, 'start');

$pluginPages['awtMetrics'] = PLUGINS.'awtMetrics'.DIRECTORY_SEPARATOR.'plugin.page.php';

if(function_exists('navbarLoader')) {
    $location = HOSTNAME.'awt-content/plugins/awtMetrics/data/icons/';

    $metrics->addItem(array('icon' => $location.'chart-line-solid.svg', 'name'=>'Metrics', 'link' => '?page=awtMetrics'));

    array_push($navbar, $metrics);
}

