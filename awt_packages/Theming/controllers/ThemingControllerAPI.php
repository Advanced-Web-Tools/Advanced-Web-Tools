<?php

use controller\Controller;
use packages\runtime\api\RuntimeControllerAPI;

final class ThemingControllerAPI extends RuntimeControllerAPI
{
    private Controller $controller;


    public function setup(): void
    {
        $this->controller = $this->getLocalObject("/controllers/ThemingController.php");

        $this->controller->controllerName = "ThemingController";
        $this->controller->setRootPath($this->runtimePath);
        $this->controller->setViewPath("/views/");
        $this->controller->localAssetPath = "/awt_packages/Theming/views/assets";

        $this->controller->eventDispatcher = $this->eventDispatcher;

    }

    public function main(): void
    {
        $this->addController($this->controller);
    }
}