<?php

namespace controller;

use controller\interfaces\IController;
use view\View;

/**
 * Abstract Controller Class
 *
 * This abstract class extends the View class and implements the
 * IController interface. It provides the foundation for all controllers,
 * including functionality to set the root directory and view paths.
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
}