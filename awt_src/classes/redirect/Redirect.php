<?php

namespace redirect;

use redirect\breadcrumbs\BreadCrumbs;

/**
 * The Redirect class extends the BreadCrumbs class and is responsible for managing
 * redirection logic, including routing, custom redirects, and going back to the previous page.
 */
class Redirect extends BreadCrumbs
{
    public array $routes = [];
    public string $redirectTo;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sets the routes for the redirection logic.
     *
     * This method assigns an array of routes to the `$routes` property.
     * Each route is a key-value pair where the key is the route name,
     * and the value is the corresponding URL.
     *
     * @param array $routes An associative array of routes.
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }


    /**
     * Sets the redirection URL based on a named route.
     *
     * This method accepts a route name, retrieves the corresponding URL from the `$routes` array,
     * and assigns it to the `$redirectTo` property.
     *
     * @param string $name The name of the route.
     * @return self Returns the instance of the Redirect class to allow method chaining.
     */
    public function route(string $name): self
    {
        $this->redirectTo = $this->routes[$name];
        return $this;
    }

    /**
     * Sets the redirection URL to the last visited page.
     *
     * This method retrieves the last visited page URL (via the `getLast()` method inherited
     * from BreadCrumbs) and assigns it to the `$redirectTo` property.
     *
     * @return self Returns the instance of the Redirect class to allow method chaining.
     */
    public function back(): self
    {
        $this->redirectTo = $this->getLast();
        return $this;
    }

    /**
     * Manually sets the redirection URL to a specific path.
     *
     * This method directly assigns a given path (or URL) to the `$redirectTo` property.
     *
     * @param string $path The URL to which the user should be redirected.
     * @return self Returns the instance of the Redirect class to allow method chaining.
     */
    public function redirect(string $path): self
    {
        $this->redirectTo = $path;
        return $this;
    }

    /**
     * Retrieves the current redirection URL.
     *
     * This method returns the value of the `$redirectTo` property, which contains
     * the URL to which the user will be redirected.
     *
     * @return string The URL to redirect to.
     */
    public function getRedirectTo(): string
    {
        return $this->redirectTo;
    }

}