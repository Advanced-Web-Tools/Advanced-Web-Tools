<?php
global $settings;
require_once './awt_dirMap.php';
require_once './awt_config.php';
require_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt_autoLoader.php';
require_once JOBS . 'awt_settings.php';
require_once JOBS . "awt_domainBuilder.php";
require_once FUNCTIONS . 'awt_errorHandler.fun.php';

use event\EventDispatcher;
use packages\installer\PackageInstaller;
use packages\manager\loader\Loader;
use redirect\Redirect;
use router\events\EDynamicRouteListener;
use router\manager\RouterManager;
use setting\Config;

if (DEBUG && REMOTE_INSTALL_FOR_DEVS && $_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/dev/install') {
    if (!isset($_FILES["package"]) || DEV_SECRET != $_POST["devSecret"]) {
        die(WEB_NAME . ": Wrong dev secret, or missing file.");
    }

    $installer = new PackageInstaller($_FILES["package"]);

    $installer->
    setDataOwner("AWT")->
    uploadPackage()->
    extractPackage()->
    installPackage()->
    transferPackageFiles()->
    extractData()->
    cleanUp();

    die("Installed on " . Config::getConfig("AWT", "Website Name")->getValue());
}

$packages = new Loader();
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

$shared = $packages->sharedObjects;

$router->eventDispatcher = $packages->eventDispatcher;

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