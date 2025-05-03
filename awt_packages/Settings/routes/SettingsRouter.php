<?php

use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
use router\Router;

final class SettingsRouter extends RuntimeRouterAPI
{

    private RuntimeControllerAPI $controller;

    public function setup(): void
    {
        $this->controller = $this->getPassable("Settings", "SettingsControllerAPI");
    }


    public function main(): void
    {
        $this->addRouter(new Router("/dashboard/settings/{category}", "index", $this->controller->getController("SettingsController")));
        $this->addRouter(new Router("/settings/change/", "changeSetting", $this->controller->getController("SettingsController"), true));
    }
}