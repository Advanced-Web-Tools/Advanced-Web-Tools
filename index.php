<?php
require_once './awt_dirMap.php';
require_once './awt_config.php';

require_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt_autoLoader.php';
require_once JOBS . 'awt_settings.php';
require_once JOBS . "awt_domainBuilder.php";

use event\EventDispatcher;
use packages\manager\loader\Loader;
use redirect\Redirect;
use router\manager\RouterManager;
use setting\Config;

$packages = new Loader();
$router = new RouterManager();
$eventDispatcher = new EventDispatcher();

$packages->eventDispatcher = $eventDispatcher;

if (Config::getConfig("AWT", "use packages")->getValue() == 'true')
    $packages->load();

$eventDispatcher = $packages->eventDispatcher;

$router->eventDispatcher = $eventDispatcher;

foreach ($packages->routers as $route) {
    $router->loadRouters($route->getRouters());
}

$page = $router->startRouter();
$page->eventDispatcher = $eventDispatcher;
try {
    if ($page instanceof Redirect) {
        $redirect = $page->getRedirectTo();
        header("Location: $redirect");
        exit();
    }

    $doc = $page->render();
} catch (Exception $e) {
    die("Internal error: " . $e->getMessage());
}

$redirect = new Redirect();
$redirect->setLast();

die($doc);