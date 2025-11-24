<?php

namespace packages\runtime\api;

use controller\Controller;
use Exception;
use object\ObjectFactory;
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
     * @return ObjectFactory|Controller The requested controller instance. If object is ObjectFactory it will be initialized only when route enters.
     */
    final public function getController(string $name): ObjectFactory|Controller
    {
        if($this->controllers[$name] instanceof ObjectFactory) {
            $this->controllers[$name]->addProperty("packageName", $this->name);
            return $this->controllers[$name];
        }
        
        $this->controllers[$name]->packageName = $this->name;
        return $this->controllers[$name];
    }

    /**
     * Adds a controller to the internal controllers array.
     *
     * To pass an ObjectFactory $type must be set to Controller.
     * @param ObjectFactory|Controller $controller The controller to be added.
     * @param string $name Optional, only used when ObjectFactory is passed.
     * @throws Exception on passed ObjectFactory without type.
     */
    protected function addController(ObjectFactory|Controller $controller, string $name = ""): void
    {
        if($controller instanceof ObjectFactory)
            if($controller->type == null && DEBUG)
                throw new Exception("RuntimeControllerAPI: To pass an ObjectFactory you must set 'type' parameter.");

        if($controller instanceof Controller) {
            $this->controllers[$controller->controllerName] = $controller;
        } else {
            $this->controllers[$name] = $controller;
        }

    }

}