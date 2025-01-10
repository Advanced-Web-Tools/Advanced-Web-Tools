<?php

namespace packages\runtime\api;

use packages\runtime\handler\enums\ERuntimeFlags;
use router\interface\IRouter;
use router\Router;

/**
 * Abstract class RuntimeRouterAPI
 *
 * The RuntimeRouterAPI class extends RuntimeAPI and implements the IRouter interface.
 * It is responsible for managing routing functionality within the runtime environment,
 * including setting up routers and handling event dispatching.
 */
abstract class RuntimeRouterAPI extends RuntimeAPI implements IRouter
{
    public array $routers;

    /**
     * Sets up the environment for the runtime router.
     *
     * This method configures the runtime by setting the necessary flags
     * for routing, passable objects, access to other instances, and event dispatching.
     */
    public function environmentSetup(): void
    {
        $this->setRuntimeFlag(ERuntimeFlags::Router);
        $this->setRuntimeFlag(ERuntimeFlags::CreatePassableObject);
        $this->setRuntimeFlag(ERuntimeFlags::AccessOtherInstances);
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }

    /**
     * Adds a Router instance to the routers' collection.
     *
     * @param Router $router The router instance to be added.
     */
    public function addRouter(Router $router): void
    {
        $router->eventDispatcher = $this->eventDispatcher;
        $this->routers[] = $router;
    }

    /**
     * Returns the array of Router instances.
     *
     * @return Router[] An array containing all added Router instances.
     */
    public function getRouters(): array
    {
        return $this->routers;
    }
}