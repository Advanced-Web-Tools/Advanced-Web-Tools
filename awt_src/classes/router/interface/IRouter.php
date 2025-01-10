<?php

namespace router\interface;

use router\Router;

/**
 * The IRouter interface defines the contract for any router manager implementation.
 * It establishes the necessary methods for adding and retrieving routers.
 */
interface IRouter
{
    /**
     * Adds a Router instance to the router manager.
     *
     * @param Router $router The Router instance to be added.
     */
    public function addRouter(Router $router): void;

    /**
     * Retrieves all Router instances managed by the router manager.
     *
     * @return array An array of Router instances.
     */
    public function getRouters(): array;

}