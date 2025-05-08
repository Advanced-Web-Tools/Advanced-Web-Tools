<?php


use Dashboard\classes\dashboard\DashboardPage;
use packages\runtime\api\RuntimeControllerAPI;

final class AccountManagerController extends RuntimeControllerAPI
{

    private DashboardPage $controllerDashboard;

    public function setup(): void
    {
        $this->controllerDashboard = $this->getLocalObject('/controllers/controller/AccountController.php');
        $this->controllerDashboard->controllerName = "AccountController";
        $this->controllerDashboard->setRootPath($this->runtimePath);
        $this->controllerDashboard->setViewPath("/views/");
        $this->controllerDashboard->localAssetPath = "/awt_packages/AccountManager/views/assets";
        $this->controllerDashboard->eventDispatcher = $this->eventDispatcher;
    }

    public function main(): void
    {
        $this->controllerDashboard->setShared($this->shared);
        $this->addController($this->controllerDashboard);
    }
}