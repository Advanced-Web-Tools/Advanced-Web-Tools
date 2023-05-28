<?php

use plugins\plugins;

$dependencies = array();
$engines = array();
$settings = array();
$navbar = array();
$widgets = array();
$aio = array();
$menu = array();
$pluginPages = array();
$pages = array();
$dashboardWidgets = array();
$loadedPlugins = array();

include_once JOBS.'awt-domainBuilder.php';
include_once FUNCTIONS . 'awt-dependenciesLoader.fun.php';
include_once FUNCTIONS . 'awt-enginesLoader.fun.php';
include_once FUNCTIONS . 'awt-pluginsLoader.fun.php';

$plugins = new plugins();

$plugins = $plugins->getPlugins();


loadAllPlugins();

include_once PLUGINS . 'defaultdashboardNavigation' . DIRECTORY_SEPARATOR . 'plugin.main.php';


ob_start();

dependenciesLoader();
enginesLoader('start');

ob_end_clean();
