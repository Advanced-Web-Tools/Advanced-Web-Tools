<?php

use controller\Controller;
use Dashboard\classes\dashboard\DashboardPage;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class AWTStoreControllerAPI extends RuntimeControllerAPI
{

    public DashboardPage $publicController;
    public Controller $proxy;
    public Controller $serviceController;

    public function environmentSetup(): void
    {
        parent::__construct();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::CreatePassableObject);
    }

    public function setup(): void
    {
        $this->publicController = $this->getLocalObject("/controllers/controller/AWTStoreController.php");
        $this->publicController->setName("AWTStoreController");
        $this->publicController->setRootPath($this->runtimePath);
        $this->publicController->setViewPath("/views/");
        $this->publicController->localAssetPath = "/awt_packages/AWTStore/views/assets";
        $this->publicController->eventDispatcher = $this->eventDispatcher;
        $this->publicController->setShared($this->shared);

        $this->proxy = $this->getLocalObject("/controllers/controller/AWTStoreProxyController.php");
        $this->proxy->setName("AWTStoreProxyController");
        $this->proxy->setRootPath($this->runtimePath);
        $this->proxy->setViewPath("/views/");


        $this->serviceController = $this->getLocalObject("/controllers/controller/AWTStoreServiceController.php");
        $this->serviceController->setName("AWTStoreServiceController");
        $this->serviceController->setRootPath($this->runtimePath);
        $this->serviceController->setViewPath("/views/");

    }

    public function main(): void
    {
        $this->addController($this->publicController);
        $this->addController($this->proxy);
        $this->addController($this->serviceController);
    }
}