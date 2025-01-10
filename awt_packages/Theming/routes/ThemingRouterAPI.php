<?php

namespace routes;

use database\DatabaseManager;
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use router\Router;

final class ThemingRouterAPI extends RuntimeRouterAPI
{

    private RuntimeControllerAPI $controllerAPI;


    public function setup(): void
    {
        $this->controllerAPI = $this->getPassable("Theming", "ThemingControllerAPI");
    }

    public function main(): void
    {

        $this->addRouter(router: new Router("/dashboard/themes", "index", $this->controllerAPI->getController("ThemingController")));
        $this->addRouter(router: new Router("/theming/customize/{name}/{theme_id}", "customize", $this->controllerAPI->getController("ThemingController")));
    }
}