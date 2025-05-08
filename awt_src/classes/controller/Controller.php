<?php

/**
 * Abstract base class for controllers.
 *
 * Provides common functionality for controllers, such as setting
 * the root directory path, view directory path, and controller name.
 *
 * This class extends the View class and implements the IController interface.
 */

namespace controller;

use /**
 * Interface IController
 *
 * Defines the contract for controllers within the application.
 * Controllers implementing this interface are responsible for
 * handling requests and returning appropriate responses.
 */
    controller\interfaces\IController;
use /**
 * The View class is responsible for rendering templates and managing the output display.
 * This class typically handles the dynamic content replacement in templates and
 * facilitates the interaction between the controller and the user interface.
 */
    view\View;

/**
 * Abstract class representing a controller, which implements the IController interface.
 *
 * This class provides functionality for managing controller-related properties,
 * including the controller name, the root directory path, and shared data. It
 * also defines methods for setting paths necessary for view rendering.
 */
abstract class Controller extends View implements IController
{
    /**
     * @var string $controllerName The name of the controller.
     */
    public string $controllerName;

    /**
     * @var string $rootDirPath The root directory path for the controller.
     */
    public string $rootDirPath;

    public array $shared = [];

    /**
     * Sets the root directory path for the controller.
     *
     * This method allows the root directory path to be set, which can be
     * used to locate views or other resources relative to this path.
     *
     * @param string $rootDirPath The root directory path to set.
     * @return void
     */
    final public function setRootPath(string $rootDirPath): void
    {
        $this->rootDirPath = $rootDirPath;
    }

    /**
     * Sets the view directory path for the controller.
     *
     * This method constructs the full path to the view directory by
     * concatenating the root directory path with the provided relative
     * path.
     *
     * @param string $path The relative path to the view directory.
     * @return void
     */
    final public function setViewPath(string $path): void
    {
        $this->viewDirectory = $this->rootDirPath . $path;
    }

    /**
     * Sets the name for controller.
     * @param string $name
     * @return void
     */
    final public function setName(string $name): void
    {
        $this->controllerName = $name;
    }
}