<?php
namespace router;

use controller\Controller;
use event\EventDispatcher;
use redirect\Redirect;
use router\events\RouteEnterEvent;
use view\View;

/**
 * The Router class handles routing in the AWT,
 * matching request paths to defined routes and invoking
 * the appropriate controller actions.
 */
class Router
{
    /**
     * @var ?string $name
     * The name of the route, if defined.
     */
    public ?string $name;

    /**
     * @var string $path
     * The path pattern for the route.
     */
    public string $path;

    /**
     * @var string $action
     * The action name to be executed when the route is matched.
     */
    public string $action;

    /**
     * @var ?string $alias
     * An optional alias for the route.
     */
    public ?string $alias;

    /**
     * @var Controller $controller
     * The controller instance associated with the route.
     */
    public Controller $controller;

    /**
     * @var EventDispatcher $eventDispatcher
     * The event dispatcher to handle events for the route.
     */
    public EventDispatcher $eventDispatcher;


    /**
     * @var bool Service
     * Determines if this route is used for processing data or other type of jobs, and should be hidden from UI.
     */
    public bool $service = false;

    /**
     * Router constructor.
     *
     * @param string $path The path pattern for the route.
     * @param string $action The action to be called.
     * @param Controller $controller The controller handling the action.
     */
    public function __construct(string $path, string $action, Controller $controller, bool $service = false)
    {
        $this->path = $path;
        $this->action = $action;
        $this->controller = $controller;
        $this->name = "";

        $this->service = $service;

        return $this;
    }

    /**
     * Sets the name of the route.
     *
     * @param string $name The name to be set.
     * @return self The Router instance for method chaining.
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets an alias for the route.
     *
     * @param string $alias The alias to be set.
     * @return self The Router instance for method chaining.
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Adds an event dispatcher to the router.
     *
     * @param EventDispatcher $eventDispatcher The event dispatcher to be set.
     * @return self The Router instance for method chaining.
     */
    public function addEventDispatcher(EventDispatcher $eventDispatcher): self
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * Matches the provided request path against the route's path pattern.
     *
     * @param string $requestPath The path of the incoming request.
     * @return ?array An associative array of matched parameters if successful, null otherwise.
     */
    public function match(string $requestPath): ?array
    {
        if ($requestPath === "/") {
            return [];
        }

        $explodedRoute = explode("/", $this->path);
        $explodedPath = explode("/", $requestPath);
        $matches = [];

        if (count($explodedRoute) !== count($explodedPath)) {
            return null;
        }

        foreach ($explodedRoute as $routeKey => $routeValue) {

            if (str_starts_with($routeValue, "{") && str_ends_with($routeValue, "}")) {
                $paramName = trim($routeValue, '{}');
                $matches[$paramName] = $explodedPath[$routeKey];
            } elseif ($routeValue !== $explodedPath[$routeKey]) {
                return null;
            }
        }

        return $matches;
    }

    /**
     * Routes the request to the appropriate controller action
     * and dispatches the RouteEnterEvent.
     *
     * @param array $params Optional parameters for the controller action.
     * @return View|Redirect The result of the controller action, either a View or Redirect instance.
     */
    public function route(array $params = []): View|Redirect
    {
        $this->eventDispatcher->dispatch(new RouteEnterEvent($this->path, $this->action, $this->controller));

        $this->controller->viewName = $this->action;
        return $this->controller->{$this->action}($params);
    }
}

