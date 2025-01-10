<?php

use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
use Quil\classes\page\PageManager;
use router\Router;

final class QuilRouter extends RuntimeRouterAPI
{
    private RuntimeControllerAPI $controller;

    public function setup(): void
    {
        $this->controller = $this->getPassable("Quil", "QuilControllers");
    }

    public function main(): void
    {
        $this->addRouter(new Router("/dashboard/pages", "index", $this->controller->getController("QuilController")));
        $this->addRouter(new Router("/dashboard/pages/{status}", "index", $this->controller->getController("QuilController")));
        $this->addRouter(new Router("/quil/page_editor/{id}", "editor", $this->controller->getController("QuilController")));

        $this->addRouter(new Router("/quil/create", "create", $this->controller->getController("QuilActionController")));
        $this->addRouter(new Router("/quil/route_create", "createRoute", $this->controller->getController("QuilActionController")));
        $this->addRouter(new Router("/quil/route_delete/{id}", "deleteRoute", $this->controller->getController("QuilActionController")));
        $this->addRouter(new Router("/quil/delete/{id}", "delete", $this->controller->getController("QuilActionController")));
        $this->addRouter(new Router("/quil/save/{id}", "save", $this->controller->getController("QuilActionController")));
        $this->addRouter(new Router("/quil/info/{id}", "getInfo", $this->controller->getController("QuilActionController")));
        $this->addRouter(new Router("/quil/datasources/{id}", "getSources", $this->controller->getController("QuilActionController")));
        $this->addRouter(new Router("/quil/add_source/{id}", "addSource", $this->controller->getController("QuilActionController")));
        $this->addRouter(new Router("/quil/update_source/{id}", "updateSource", $this->controller->getController("QuilActionController")));

        $this->addRouter(new Router("/quil/delete_source/{id}", "delSource", $this->controller->getController("QuilActionController")));

        $pm = new PageManager();
        foreach ($pm->fetchRoutes()->returnRoutes() as $route) {
            $this->addRouter(new Router($route->route, "customPage", $this->controller->getController("CustomPageController")));
        }


    }
}