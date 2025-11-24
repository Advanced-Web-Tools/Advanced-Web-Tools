<?php

if (PHP_SAPI !== 'cli')
    die("This script can only be run from a command line.");

$_SERVER['REQUEST_URI'] = '/CLI/';

global $settings;
require_once './awt_dirMap.php';
require_once './awt_config.php';
require_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt_autoLoader.php';
require_once JOBS . 'awt_settings.php';
require_once JOBS . "awt_domainBuilder.php";

use cli\commands\PackageManagerCommand;
use cli\commands\RoutesCommand;
use event\EventDispatcher;
use router\manager\RouterManager;
use router\events\EDynamicRouteListener;
use packages\manager\loader\Loader;
use setting\Config;
use cli\CLIHandler;
use cli\commands\VersionCommand;
use cli\commands\HelloCommand;
use cli\commands\ClearCommand;

$handler = new CLIHandler();

$handler->addCommand(new ClearCommand());
$handler->addCommand(new VersionCommand());
$handler->addCommand(new HelloCommand());
$handler->addCommand(new PackageManagerCommand());

$rc = new RoutesCommand();

$packages = new Loader();
$packages->CLIHandler = $handler;
$shared["AWT"]["Settings"] = $settings;

$packages->sharedObjects = $shared;

$router = new RouterManager();
$eventDispatcher = new EventDispatcher();

$router->eventDispatcher = $eventDispatcher;

$dynamicRouteEvent = new EDynamicRouteListener();

$dynamicRouteEvent->addManager($router);

$eventDispatcher->addListener("route.dynamic.add", $dynamicRouteEvent);

$packages->eventDispatcher = $eventDispatcher;

if (Config::getConfig("AWT", "use packages")->getValue() == 'true') {
    try {
        $packages->load();
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

foreach ($packages->routers as $route) {
    $router->loadRouters($route->getRouters());
    $rc->addRoutes($route->getRouters());
}

$handler->addCommand($rc);

$handler = $packages->CLIHandler;

$argv = $_SERVER['argv'] ?? [];
array_shift($argv);

$firstCommand = null;
if (count($argv) > 0) {
    $firstCommand = [
        'cmd' => $argv[0],
        'args' => array_slice($argv, 1)
    ];
}

while (true) {
    if ($firstCommand !== null) {
        $cmd = $firstCommand['cmd'];
        $args = $firstCommand['args'];
        $firstCommand = null;
    } else {
        $input = readline("awt> ");
        if (!$input) continue;

        $parts = explode(" ", $input);
        $cmd = array_shift($parts);
        $args = $parts;
    }

    if ($cmd === 'help') {
        $handler->help($args[0] ?? null);
        continue;
    }

    if ($cmd === 'clear') {
        echo "\033[2J\033[;H";
        continue;
    }

    $handler->execute($cmd, $args);
}
