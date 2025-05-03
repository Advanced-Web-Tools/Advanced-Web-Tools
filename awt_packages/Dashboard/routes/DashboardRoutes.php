<?php
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
use router\Router;

final class DashboardRoutes extends RuntimeRouterAPI
{
    private RuntimeControllerAPI $controller;

    public function setup(): void
    {
        $this->controller = $this->getPassable("Dashboard", "DashboardController");
    }

    public function main(): void
    {
        //Views
        $this->addRouter(new Router("/dashboard", "index", $this->controller->getController("DashboardController")));
        $this->addRouter(new Router("/dashboard/login/{status}", "login", $this->controller->getController("DashboardController")));

        //Actions
        $this->addRouter(new Router("/dashboard/loginAction", "loginAction", $this->controller->getController("ActionController"), true));
        $this->addRouter(new Router("/dashboard/logout", "logoutAction", $this->controller->getController("ActionController"), true));
    }
}