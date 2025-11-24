<?php

use controller\Controller;
use Dashboard\classes\dashboard\DashboardPage;
use object\ObjectFactory;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class DashboardController extends RuntimeControllerAPI
{
    private ObjectFactory $controller;
    private ObjectFactory $actionController;

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }

    public function setup(): void
    {

        $props = [
            "controllerName" => "DashboardController",
            "localAssetPath" => "/awt_packages/Dashboard/views/assets",
            "eventDispatcher" => $this->eventDispatcher,
        ];

        $methods = [
            "setRootPath",
            "setViewPath",
            "setShared"
        ];

        $methodArgs = [
            "setRootPath" =>
            [
                $this->runtimePath,
            ],
            "setViewPath" => [
                "/views/"
            ],
            "setShared" => [
                $this->shared
            ]
        ];


        $objectFactory = new ObjectFactory();
        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/DashboardMainController.php");
            $objectFactory->setType(Controller::class);
            $objectFactory->setMethodCalls($methods);
            $objectFactory->setProperties($props);
            $objectFactory->setMethodArgs($methodArgs);
            $this->controller = $objectFactory;
        } catch (Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }

        $props = [
            "controllerName" => "ActionController",
        ];

        $objectFactory = new ObjectFactory();

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/DashboardActionController.php");
            $objectFactory->setType(Controller::class);
            $objectFactory->setProperties($props);
            $this->actionController = $objectFactory;
        } catch (Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }
    }

    public function main(): void
    {
        try {
            $this->addController($this->controller, "DashboardController");
            $this->addController($this->actionController, "ActionController");
        } catch (Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }
    }
}