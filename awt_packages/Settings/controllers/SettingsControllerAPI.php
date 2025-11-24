<?php
use Dashboard\classes\dashboard\DashboardPage;
use object\ObjectFactory;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use Exception;

final class SettingsControllerAPI extends RuntimeControllerAPI
{
    private ObjectFactory $controller;

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
            "localAssetPath" => "/awt_packages/Settings/views/assets/",
            "eventDispatcher" => $this->eventDispatcher
        ];

        $methodCalls = [
            "setName",
            "setRootPath",
            "setViewPath",
            "setShared",
        ];

        $methodArgs = [
            "setName" => ["SettingsController"],
            "setRootPath" => [$this->runtimePath],
            "setViewPath" => ["/views/"],
            "setShared" => [$this->shared],
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/SettingsController.php");
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
    }

    public function main(): void
    {
        try {
            $this->addController($this->controller, "SettingsController");
        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }
    }
}