<?php

use controller\Controller;
use Dashboard\classes\dashboard\DashboardPage;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class MediaCenterController extends RuntimeControllerAPI
{
    private DashboardPage $controller;
    private Controller $action;

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }

    public function setup(): void
    {
        $this->controller = $this->getLocalObject("/controllers/controller/MediaController.php");
        $this->controller->setRootPath($this->runtimePath);
        $this->controller->setViewPath("/views/");
        $this->controller->controllerName = "MediaCenter";
        $this->controller->localAssetPath = "/awt_packages/MediaCenter/views/assets";
        $this->controller->eventDispatcher = $this->eventDispatcher;

        $this->action = $this->getLocalObject("/controllers/controller/MediaAction.php");
        $this->action->controllerName = "MediaAction";
        $this->action->eventDispatcher = $this->eventDispatcher;
    }

    public function main(): void
    {
        $this->controller->setShared($this->shared);
        $this->action->shared = $this->shared;

        $this->addController($this->controller);
        $this->addController($this->action);
    }
}