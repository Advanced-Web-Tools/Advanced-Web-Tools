<?php

namespace view\interfaces;

use redirect\Redirect;
use view\View;

/**
 * Interface IView
 *
 * Defines the contract for view classes.
 * Classes implementing this interface should provide a method for handling
 * the default view logic, the index action.
 */
interface IView
{

    /**
     * Handles the index action of the view.
     *
     * This method should return an instance of the View class or a Redirect.
     *
     * @return View|Redirect The View instance to be rendered or a Redirect instance to another location.
     */
    public function index(): View|Redirect;
}