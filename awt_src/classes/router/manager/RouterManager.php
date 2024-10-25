<?php
namespace router\manager;

use event\EventDispatcher;
use redirect\Redirect;
use router\Router;
use view\View;

/**
 * The RouterManager class manages a collection of routers,
 * handling the addition, retrieval, and routing of requests
 * to the appropriate route based on the current request path.
 */
final class RouterManager
{
    private array $routesName = [];
    private array $routesPath = [];
    private string $currentPath;

    /**
     * @var EventDispatcher $eventDispatcher
     * The event dispatcher for handling events related to routing.
     */
    public EventDispatcher $eventDispatcher;

    public function __construct()
    {
        $this->currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Adds a Router instance to the manager.
     * If the router does not have a name, it assigns one based on the count of existing routes.
     *
     * @param Router $route The Router instance to add.
     */
    public function addRouter(Router $route): void
    {
        if ($route->name === null) {
            $route->name = count($this->routesName);
        }

        $route->eventDispatcher = $this->eventDispatcher;

        $this->routesName[$route->name] = $route;
        $this->routesPath[$route->path] = $route;
    }

    /**
     * Loads multiple Router instances into the manager.
     *
     * @param array $routers An array of Router instances to load.
     */
    public function loadRouters(array $routers): void
    {
        foreach ($routers as $router) {
            $this->addRouter($router);
        }
    }

    /**
     * Retrieves all routes managed by the RouterManager.
     *
     * @return array An associative array of routes indexed by their names.
     */
    public function getRoutes(): array
    {
        return $this->routesName;
    }

    /**
     * Retrieves a specific route by its name.
     *
     * @param string $name The name of the route to retrieve.
     * @return ?Router The Router instance if found, null otherwise.
     */
    public function getRouteByName(string $name): ?Router
    {
        return $this->routesName[$name] ?? null;
    }

    /**
     * Retrieves a specific route by its path.
     *
     * @param string $path The path of the route to retrieve.
     * @return ?Router The Router instance if found, null otherwise.
     */
    public function getRouteByPath(string $path): ?Router
    {
        return $this->routesPath[$path] ?? null;
    }

    /**
     * Starts the routing process, matching the current path against the defined routes.
     * If a matching route is found, it invokes the corresponding action.
     *
     * @return View|Redirect The result of the route action, either a View or Redirect instance.
     */
    public function startRouter(): View|Redirect
    {
        foreach ($this->routesPath as $route) {
            $params = $route->match($this->currentPath);

            if ($params !== null) {
                return $route->route($params);
            }
        }

        $this->handleNotFound();
        exit();
    }

    /**
     * Handles 404 Not Found responses when no routes match the current path.
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        echo "404 Not Found";
    }
}

