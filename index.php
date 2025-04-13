<?php
require_once './awt_dirMap.php';
require_once './awt_config.php';

require_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt_autoLoader.php';
require_once JOBS . 'awt_settings.php';
require_once JOBS . "awt_domainBuilder.php";
require_once FUNCTIONS . 'awt_errorHandler.fun.php';

use event\EventDispatcher;
use packages\manager\loader\Loader;
use redirect\Redirect;
use router\events\EDynamicRouteListener;
use router\manager\RouterManager;
use setting\Config;

$packages = new Loader();
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
//        die($e->getMessage());
    }
}

$router->eventDispatcher = $packages->eventDispatcher;;

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