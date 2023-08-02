<?php

use settings\settings;

$settings = new settings;

$name = "awtDashboardWidgets";
$version = "0.0.1";

$src = PLUGINS.$name.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
$dependenciesPath = $src.'awtWidgets.class.php';

addDependencie('high', $name, $dependenciesPath, $version);

if(!$settings->checkIfSettingExists("Show default AWT widgets")) {
    $settings->createSetting("Show default AWT widgets", "true");
    $settings->fetchSettings();
}

if(function_exists('loadAllWidgets') && defined("DASHBOARD") && $settings->getSettingsValue("Show default AWT widgets") == "true") {

    $plugins_widget = array("name" => "Plugins Widget", "src" => $src."pluginsWidget.php");
    pushToWidgets($plugins_widget);
    
    $notification_widget = array("name" => "Notification Widget", "src" => $src."notificationWidget.php");
    pushToWidgets($notification_widget);

}