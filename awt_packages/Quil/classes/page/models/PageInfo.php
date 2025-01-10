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
    public ?PageRoute $route = null;
    public ?AdminModel $admin = null;
    public ?int $route_id;
    public int $created_by;
    public string $name;
    public ?string $description;
    public ?string $creation_date;
    public function __construct(int $id)
    {
        parent::__construct();
        $this->selectByID($id, "quil_page");
        if($this->route_id !== null)
            $this->route = new PageRoute($this->route_id);
        if($this->admin !== null)
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