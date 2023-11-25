<?php

use settings\settings;

// use admin\navbar;
// $metrics = new navbar;

$settings = new settings;

$name = 'awtMetrics';
$version = "0.0.1";
$src = PLUGINS.'awtMetrics'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
$enginePath = $src.'awtMetrics.php';
$dependenciesPath = $src.'awtMetrics.class.php';

include_once $dependenciesPath;

addDependency('high', $name, $dependenciesPath, $version);

addEngine($name, $enginePath, $version, 'start');

// $pluginPages['awtMetrics'] = PLUGINS.'awtMetrics'.DIRECTORY_SEPARATOR.'plugin.page.php';

// if(function_exists('navbarLoader')) {
//     $location = HOSTNAME.'awt-content/plugins/awtMetrics/data/icons/';

//     $metrics->addItem(array('icon' => $location.'chart-line-solid.svg', 'name'=>'Metrics', 'link' => '?page=awtMetrics'));

//     array_push($navbar, $metrics);
// }

$collection = "Metrics Blocks";
createBlockCollection($collection);

$defaultPath = PLUGINS.$name.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."blocks".DIRECTORY_SEPARATOR;

$tester = array("name" => "Metrics", "path" => $defaultPath."most-visited.php");

addBlock($tester, $collection);

if(isset($api_executors)) {
    addToApiExecution("getViewes", $class = new awtMetrics);
}


if(!$settings->checkIfSettingExists("Show Metrics widgets")) {
    $settings->createSetting("Show Metrics widgets", "true");
    $settings->fetchSettings();
}


if(function_exists('loadAllWidgets') && defined("DASHBOARD") && $settings->getSettingsValue("Show Metrics widgets") == "true") {
    $widget = array("name" => "Metrics Widget", "src" => $src."metricsWidget.php");
    pushToWidgets($widget);
}


