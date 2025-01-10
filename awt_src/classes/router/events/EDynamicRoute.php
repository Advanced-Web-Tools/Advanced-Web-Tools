<?php

namespace router\events;

use event\interfaces\IEvent;
use router\Router;

final class EDynamicRoute implements IEvent
{

    private array $routes = [];

    public function getName(): string
    {
        return "route.dynamic.add";
    }

    public function addRoute(Router $router): void
    {
        $this->routes[] = $router;
    }

    public function bundle(): array
    {
        return $this->routes;
    }
}