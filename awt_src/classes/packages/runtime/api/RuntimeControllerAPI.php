<?php

namespace packages\runtime\api;

use controller\Controller;
use packages\runtime\handler\enums\ERuntimeFlags;

/**
 * Abstract class RuntimeControllerAPI
 *
 * The RuntimeControllerAPI class extends RuntimeAPI and is responsible for managing
 * controllers within the runtime environment. It sets up the environment and provides
 * access to registered controllers.
 */
abstract class RuntimeControllerAPI extends RuntimeAPI
{
    public array $controllers;

    /**
     * Sets up the environment for the runtime controller.
     *
     * This method configures the runtime by setting the necessary flags
     * for controllers and passable objects.
     */
    public function environmentSetup(): void
    {
        $this->setRuntimeFlag(ERuntimeFlags::Controller);
        $this->setRuntimeFlag(ERuntimeFlags::CreatePassableObject);
    }

    /**
     * Retrieves a controller by its name.
     *
     * @param string $name The name of the controller to retrieve.
     * @return Controller The requested controller instance.
     */
    final public function getController(string $name): Controller
    {
        $this->controllers[$name]->packageName = $this->name;
        return $this->controllers[$name];
    }

    /**
     * Adds a controller to the internal controllers array.
     *
     * @param Controller $controller The controller to be added.
     */
    protected function addController(Controller $controller): void
    {
        $this->controllers[$controller->controllerName] = $controller;
    }

}