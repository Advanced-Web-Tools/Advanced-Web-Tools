<?php

use controller\Controller;
use Dashboard\classes\dashboard\DashboardPage;
use object\ObjectFactory;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use Exception;

final class MediaCenterController extends RuntimeControllerAPI
{
    private ObjectFactory $controller;
    private ObjectFactory $action;

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::CreatePassableObject);
    }

    public function setup(): void
    {
        $objectFactory = new ObjectFactory();
        $props = [
            "localAssetPath" => "/awt_packages/MediaCenter/views/assets",
            "eventDispatcher" => $this->eventDispatcher,
        ];
        $methodCalls = [
            "setRootPath",
            "setViewPath",
            "setName",
            "setShared",
        ];
        $methodArgs = [
            "setRootPath" => [$this->runtimePath],
            "setViewPath" => ["/views/"],
            "setName" => ["MediaCenter"],
            "setShared" => [$this->shared],
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/MediaController.php");
            $objectFactory->setProperties($props);
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(DashboardPage::class);
            $this->controller = $objectFactory;
        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }

        $objectFactory = new ObjectFactory();
        $props = [
            "eventDispatcher" => $this->eventDispatcher,
        ];
        $methodCalls = [
            "setName",
            "setShared"
        ];
        $methodArgs = [
            "setName" => ["MediaAction"],
            "setShared" => [$this->shared]
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/MediaAction.php");
            $objectFactory->setProperties($props);
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(Controller::class);
            $this->action = $objectFactory;
        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }
    }

    public function main(): void
    {
        try {
            $this->addController($this->controller, "MediaCenter");
            $this->addController($this->action, "MediaAction");
        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }
    }
}