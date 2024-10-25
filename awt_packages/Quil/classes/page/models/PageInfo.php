<?php

namespace Quil\classes\page\models;

use admin\model\AdminModel;
use model\Model;

/**
 * Class PageInfo
 *
 * - Part of `Quil` package.
 * - Use with `Dashboard` package.
 * - A model.
 *
 * Manages information about custom pages.
 */
class PageInfo extends Model
{
    public ?PageRoute $route;
    public ?AdminModel $admin;
    public int $route_id;
    public int $created_by;
    public string $name;

    public function __construct(int $id)
    {
        parent::__construct();
        $this->selectByID($id, "quil_page");
        $this->route = new PageRoute($this->route_id);
        $this->admin = new AdminModel($this->created_by);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoute(): string
    {
        return $this->route->route;
    }

}