<?php

namespace router\events;

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
    public string $controllerName;

    /**
     * RouteEnterEvent constructor.
     *
     * @param string $path The path of the route.
     * @param string $action The action associated with the route.
     * @param string $controllerName The name of the controller handling the route.
     */
    public function __construct(string $path, string $action, string $controllerName)
    {
        $this->path = $path;
        $this->action = $action;
        $this->controllerName = $controllerName;
    }

    public function getName(): string
    {
        return 'router.enter';
    }

    public function bundle(): array
    {
        return ["path" => $this->path, "action" => $this->action, "controllerName" => $this->controllerName];
    }


}
