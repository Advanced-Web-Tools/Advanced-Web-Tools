<?php

use controller\Controller;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class QuilControllers extends RuntimeControllerAPI
{

    private Controller $controller;
    private Controller $actionController;

    private Controller $customPageController;
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

        $this->customPageController = $this->getLocalObject("/controllers/controller/CustomPageController.php");
        $this->customPageController->controllerName = "CustomPageController";
        $this->customPageController->eventDispatcher = $this->eventDispatcher;
        $this->customPageController->setRootPath($this->runtimePath);
        $this->customPageController->setViewPath("/views/");
        $this->customPageController->localAssetPath = "/awt_packages/Quil/views/assets";
    }

    public function main(): void
    {
        $this->addController($this->controller);
        $this->addController($this->actionController);
        $this->addController($this->customPageController);
    }
}