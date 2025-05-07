<?php

namespace router\events;

use controller\Controller;
use event\interfaces\IEvent;

/**
 * Class RouteEnterEvent
 *
 * Represents an event that is triggered when a route is entered in the router.
 * This event contains information about the route's path, action, and the controller handling the request.
 */
class RouteEnterEvent implements IEvent
{

    /**
     * @var string The path of the route that has been entered.
     */
    public string $path;

    /**
     * @var string The action associated with the route.
     */
    public string $action;

    /**
     * @var string The name of the controller handling the route.
     */
    public Controller $controller;

    /**
     * RouteEnterEvent constructor.
     *
     * @param string $path The path of the route.
     * @param string $action The action associated with the route.
     * @param string $controller Controller that handles the route.
     */
    public function __construct(string $path, string $action, Controller $controller)
    {
        $this->path = $path;
        $this->action = $action;
        $this->controller = $controller;
    }

    public function getName(): string
    {
        return 'router.enter';
    }

    public function bundle(): array
    {
        return ["path" => $this->path, "action" => $this->action, "controller" => $this->controller];
    }


}
