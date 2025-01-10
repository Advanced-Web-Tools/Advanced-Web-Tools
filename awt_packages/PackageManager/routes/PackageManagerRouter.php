<?php

use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
use router\Router;
final class PackageManagerRouter extends RuntimeRouterAPI
{
    private RuntimeControllerAPI $controller;

    public function setup(): void
    {
        $this->controller = $this->getPassable("Package Manager", "PackageManagerControllers");
    }

    public function main(): void
    {
        $this->addRouter(new Router("/dashboard/package_manager/", "index", $this->controller->getController("PackageManagerController")));
        $this->addRouter(new Router("/dashboard/package_manager/{filter}", "index", $this->controller->getController("PackageManagerController")));
        $this->addRouter(new Router("/dashboard/package_manager/{filter}/{status}", "index", $this->controller->getController("PackageManagerController")));

        $this->addRouter(new Router("/package_manager/installer/install", "installPackage", $this->controller->getController("PackageManagerController")));
        $this->addRouter(new Router("/package_manager/installer/uninstall/{id}", "uninstallPackage", $this->controller->getController("PackageManagerController")));

        $this->addRouter(new Router("/package_manager/actions/disable/{id}", "disableAction", $this->controller->getController("PackageManagerController")));
        $this->addRouter(new Router("/package_manager/actions/enable/{id}", "enableAction", $this->controller->getController("PackageManagerController")));
    }
}