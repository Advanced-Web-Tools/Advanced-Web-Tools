<?php
use controller\Controller;
use object\ObjectFactory;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class AccountManagerController extends RuntimeControllerAPI
{
    private ObjectFactory $controllerDashboard;


    public function setup(): void
    {

        $objectFactory = new ObjectFactory();

        $props = [
            "controllerName" => "AccountController",
            "localAssetPath" => "/awt_packages/AccountManager/views/assets",
            "eventDispatcher" => $this->eventDispatcher,
        ];

        $methods = [
            "setRootPath",
            "setViewPath",
            "setShared"
        ];

        $methodArgs = [
            "setRootPath" => [$this->runtimePath],
            "setViewPath" => ["/views/"],
            "setShared" => [$this->shared]
        ];

        try {
            $objectFactory->setClassPath($this->runtimePath . "/controllers/controller/AccountController.php");
            $objectFactory->setType(Controller::class);
            $objectFactory->setMethodCalls($methods);
            $objectFactory->setProperties($props);
            $objectFactory->setMethodArgs($methodArgs);
            $this->controllerDashboard = $objectFactory;
        } catch (Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }
    }

    public function main(): void
    {
        try {
            $this->addController($this->controllerDashboard, "AccountController");
        } catch (Exception $e) {
            if(DEBUG)
                die($e->getMessage());
        }
    }
}