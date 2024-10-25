<?php

namespace Quil\classes\page\models;

use model\Model;

/**
 * Class PageRoute
 *
 * - Part of `Quil` package.
 * - Use with `Dashboard` package.
 * - A model.
 *
 * Manages custom page routes.
 */
class PageRoute extends Model
{
    public string $route;

    public function __construct(?int $id)
    {
        parent::__construct();
        if($id !== null) {
            $this->selectByID($id, "quil_page_route");
        }
    }
}