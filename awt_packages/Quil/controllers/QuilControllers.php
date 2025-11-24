<?php

use controller\Controller;
use object\ObjectFactory;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use Exception;

final class QuilControllers extends RuntimeControllerAPI
{

    private ObjectFactory $controller;
    private ObjectFactory $actionController;
    private ObjectFactory $customPageController;

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::CreatePassableObject);
    }

    public function setup(): void
    {
        // QuilController
        $objectFactory = new ObjectFactory();
        $props = [
            "localAssetPath" => "/awt_packages/Quil/views/assets",
            "eventDispatcher" => $this->eventDispatcher
        ];
        $methodCalls = [
            "setRootPath",
            "setViewPath",
            "setName",
            "setShared",
            "setPageManager"
        ];
        $methodArgs = [
            "setRootPath" => [$this->runtimePath],
            "setViewPath" => ["/views/"],
            "setName" => ["QuilController"],
            "setShared" => [$this->shared],
            "setPageManager" => [$this->getShared("Quil", "PageManager")]
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/QuilController.php");
            $objectFactory->setProperties($props);
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(Controller::class);
            $this->controller = $objectFactory;
        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }

        // QuilActionController
        $objectFactory = new ObjectFactory();
        $props = [];
        $methodCalls = [
            "setName",
            "setShared"
        ];
        $methodArgs = [
            "setName" => ["QuilActionController"],
            "setShared" => [$this->shared]
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/QuilActionController.php");
            $objectFactory->setProperties($props);
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(Controller::class);
            $this->actionController = $objectFactory;
        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }

        // CustomPageController
        $objectFactory = new ObjectFactory();
        $props = [
            "eventDispatcher" => $this->eventDispatcher,
            "localAssetPath" => "/awt_packages/Quil/views/assets"
        ];
        $methodCalls = [
            "setName",
            "setRootPath",
            "setViewPath",
        ];
        $methodArgs = [
            "setName" => ["CustomPageController"],
            "setRootPath" => [$this->runtimePath],
            "setViewPath" => ["/views/"],
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/CustomPageController.php");
            $objectFactory->setProperties($props);
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(Controller::class);
            $this->customPageController = $objectFactory;
        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }
    }

    public function main(): void
    {
        try {
            $this->addController($this->controller, "QuilController");
            $this->addController($this->actionController, "QuilActionController");
            $this->addController($this->customPageController, "CustomPageController");
        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }
    }
}