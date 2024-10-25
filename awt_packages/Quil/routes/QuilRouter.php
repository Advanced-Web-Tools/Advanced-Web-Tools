<?php

use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
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
        $this->addRouter(new Router("/quil/delete/{id}", "delete", $this->controller->getController("QuilActionController")));
    }
}