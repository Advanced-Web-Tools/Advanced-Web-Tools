<?php

use controller\Controller;
use Dashboard\classes\dashboard\DashboardPage;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class DashboardController extends RuntimeControllerAPI
{
    private DashboardPage $controller;
    private Controller $actionController;

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }

    public function setup(): void
    {
        $this->controller = $this->getLocalObject("/controllers/controller/DashboardMainController.php");
        $this->controller->setRootPath($this->runtimePath);
        $this->controller->setViewPath("/views/");
        $this->controller->controllerName = "DashboardController";
        $this->controller->localAssetPath = "/awt_packages/Dashboard/views/assets";
        $this->controller->eventDispatcher = $this->eventDispatcher;


        $this->actionController = $this->getLocalObject("/controllers/controller/DashboardActionController.php");
        $this->actionController->controllerName = "ActionController";
    }

    public function main(): void
    {
        $this->controller->setShared($this->shared);
        $this->addController($this->controller);
        $this->addController($this->actionController);
    }
}