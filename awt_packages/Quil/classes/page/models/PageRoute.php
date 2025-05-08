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

    public function __construct(?array $data)
    {
        parent::__construct();

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $this->model_source = "quil_page_route";
        $this->id_column = "id";
        $this->model_id = $data['id'];

    }
}