<?php

use controller\Controller;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class QuilControllers extends RuntimeControllerAPI
{

    private Controller $controller;
    private Controller $actionController;

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }

    public function setup(): void
    {
        $this->controller = $this->getLocalObject("/controllers/controller/QuilController.php");
        $this->controller->setRootPath($this->runtimePath);
        $this->controller->setViewPath("/views/");
        $this->controller->localAssetPath = "/awt_packages/Quil/views/assets";
        $this->controller->controllerName = "QuilController";
        $this->controller->eventDispatcher = $this->eventDispatcher;

        $this->actionController = $this->getLocalObject("/controllers/controller/QuilActionController.php");
        $this->actionController->controllerName = "QuilActionController";

    }

    public function main(): void
    {
        $this->addController($this->controller);
        $this->addController($this->actionController);
    }
}