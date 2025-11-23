<?php

use controller\Controller;
use Dashboard\classes\dashboard\DashboardPage;
use object\ObjectFactory;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class AWTStoreControllerAPI extends RuntimeControllerAPI
{

    public ObjectFactory $publicController;
    public ObjectFactory $proxy;
    public ObjectFactory $serviceController;

    public function environmentSetup(): void
    {
        parent::__construct();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::CreatePassableObject);
    }

    public function setup(): void
    {
        $objectFactory = new ObjectFactory();
        $props = [
            "localAssetPath" => "/awt_packages/AWTStore/views/assets",
            "eventDispatcher" => $this->eventDispatcher
        ];

        $methodCalls = [
            "setName",
            "setRootPath",
            "setViewPath",
            "setShared",
        ];

        $methodArgs = [
            "setName" => ["AWTStoreController"],
            "setRootPath" => [$this->runtimePath],
            "setViewPath" => ["/views/"],
            "setShared" => [$this->shared],
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/AWTStoreController.php");
            $objectFactory->setProperties($props);
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(Controller::class);
            $this->publicController = $objectFactory;
        } catch (Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }


        //AWTStoreProxyController
        $objectFactory = new ObjectFactory();
        $methodCalls = [
            "setName",
            "setRootPath"
        ];
        $methodArgs = [
            "setName" => ["AWTStoreProxyController"],
            "setRootPath" => [$this->runtimePath]
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/AWTStoreProxyController.php");
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(Controller::class);
            $this->proxy = $objectFactory;
        } catch(Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }


        //AWTStoreServiceController
        $objectFactory = new ObjectFactory();
        $methodArgs = [
            "setName" => ["AWTStoreServiceController"],
            "setRootPath" => [$this->runtimePath]
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/AWTStoreServiceController.php");
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(Controller::class);
            $this->serviceController = $objectFactory;
        } catch(Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }
    }

    public function main(): void
    {
        try {
            $this->addController($this->publicController, "AWTStoreController");
            $this->addController($this->proxy, "AWTStoreProxyController");
            $this->addController($this->serviceController, "AWTStoreServiceController");
        } catch (Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }
    }
}