<?php

use controller\Controller;
use packages\runtime\api\RuntimeControllerAPI;

final class PackageManagerControllers extends RuntimeControllerAPI
{

    private Controller $controller;

    public function setup(): void
    {
        $this->controller = $this->getLocalObject("/controllers/controller/PackageManagerController.php");
        $this->controller->controllerName = "PackageManagerController";
        $this->controller->setRootPath($this->runtimePath);
        $this->controller->setViewPath("/view/");
        $this->controller->localAssetPath = "/awt_packages/PackageManager/view/assets";
        $this->controller->eventDispatcher = $this->eventDispatcher;
    }

    public function main(): void
    {
        $this->addController($this->controller);
    }
}