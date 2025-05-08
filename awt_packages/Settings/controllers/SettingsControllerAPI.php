<?php
use Dashboard\classes\dashboard\DashboardPage;
use packages\runtime\api\RuntimeControllerAPI;

final class SettingsControllerAPI extends RuntimeControllerAPI
{
    private DashboardPage $controller;

    public function setup(): void
    {
        $this->controller = $this->getLocalObject("/controllers/SettingsController.php");

        $this->controller->setShared($this->shared);

        $this->controller->controllerName = "SettingsController";

        $this->controller->setRootPath($this->runtimePath);

        $this->controller->setViewPath("/views/");

        $this->controller->localAssetPath = "/awt_packages/Settings/views/assets/";

        $this->controller->eventDispatcher = $this->eventDispatcher;
    }

    public function main(): void
    {
        $this->controller->setShared($this->shared);
        $this->addController($this->controller);
    }
}