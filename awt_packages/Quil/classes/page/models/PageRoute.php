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

    public function __construct(array|int $data)
    {
        parent::__construct();


        if(!is_array($data)) {
            $this->selectByID($data, "quil_page_route", "id");
            return;
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $this->model_source = "quil_page_route";
        $this->id_column = "id";
        $this->model_id = $data['id'];

    }
}