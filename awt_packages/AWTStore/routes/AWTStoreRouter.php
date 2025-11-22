<?php

use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use router\Router;

final class AWTStoreRouter extends RuntimeRouterAPI
{

    public RuntimeControllerAPI $controllerApi;
    public function environmentSetup(): void
    {
        parent::environmentSetup();

        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }


    public function setup(): void
    {
        $this->controllerApi = $this->getPassable("AWTStore", "AWTStoreControllerAPI");
    }

    public function main(): void
    {
        $this->addRouter(new Router("/dashboard/store", "index", $this->controllerApi->getController("AWTStoreController")));
        $this->addRouter(new Router("/dashboard/store/view", "store", $this->controllerApi->getController("AWTStoreController")));

        //Proxy
        $this->addRouter(new Router("/dashboard/store/proxy", "index", $this->controllerApi->getController("AWTStoreProxyController")));

        //Service
        $this->addRouter(new Router("/dashboard/store/service/install", "index", $this->controllerApi->getController("AWTStoreServiceController"), true));
        $this->addRouter(new Router("/dashboard/store/service/update/{local_id}", "index", $this->controllerApi->getController("AWTStoreServiceController"), true));
    }
}