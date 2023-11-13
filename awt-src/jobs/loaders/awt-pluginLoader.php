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
$pluginBlocks = array();
$loadedBlocks = array();

include_once JOBS . 'awt-domainBuilder.php';
include_once FUNCTIONS . 'awt-Api.fun.php';
include_once FUNCTIONS . 'awt-dependenciesLoader.fun.php';
include_once FUNCTIONS . 'awt-enginesLoader.fun.php';
include_once FUNCTIONS . 'awt-pluginBlocks.fun.php';
include_once FUNCTIONS . 'awt-dashboardWidgetsLoader.fun.php';
include_once FUNCTIONS . 'awt-pluginsLoader.fun.php';

$plugins = new plugins();

include_once PLUGINS . 'defaultDashboardNavigation' . DIRECTORY_SEPARATOR . 'plugin.main.php';
include_once PLUGINS . 'awtDefaultBlocks' . DIRECTORY_SEPARATOR . 'plugin.main.php';

$plugins = $plugins->getPlugins();

if (USE_PLUGINS == 'true')
    loadAllPlugins();

// ob_start();

dependenciesLoader();

enginesLoader('start');

// ob_end_clean();