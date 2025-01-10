<?php

namespace controller\interfaces;

use redirect\Redirect;
use view\View;

/**
 * Interface IController
 *
 * This interface defines the contract for controller classes in the
 * application. It outlines the required methods that any implementing
 * controller must provide, including methods for setting paths and
 * handling requests.
 */
interface IController
{
    /**
     * Sets the root directory path for the controller.
     *
     * This method allows the implementing controller to define the root
     * directory path, which can be used to locate resources relative
     * to this path.
     *
     * @param string $rootDirPath The root directory path to set.
     * @return void
     */
    public function setRootPath(string $rootDirPath): void;

    /**
     * Sets the view directory path for the controller.
     *
     * This method enables the implementing controller to specify the
     * path to the view directory, which is used to locate the views
     * associated with the controller's actions.
     *
     * @param string $viewDirPath The path to the view directory to set.
     * @return void
     */
    public function setViewPath(string $viewDirPath): void;

    /**
     * Handles the index action of the controller.
     *
     * This method is responsible for processing the index action, which
     * typically serves as the default action for a controller. It accepts
     * parameters and returns either a View object or a Redirect object
     * based on the action's outcome.
     *
     * @param array|string $params The parameters for the index action, which
     * may be an array or a string.
     * @return View|Redirect Returns a View object for rendering a view, or
     * a Redirect object for redirecting to another location.
     */
    public function index(array|string $params): View|Redirect;
}