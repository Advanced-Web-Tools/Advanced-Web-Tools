<?php

namespace Quil\classes\page\models;

use model\interfaces\IRelationBelongs;
use model\interfaces\IRelationHasMany;
use model\interfaces\IRelationWith;
use model\Model;


class QuilPage extends Model implements IRelationWith, IRelationBelongs, IRelationHasMany
{
    public function __construct(?int $id)
    {
        parent::__construct();

        $this->selectByID($id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoute(): string
    {
        return $this->route->path;
    }

    public function belongsTo(): array
    {
        return [
            "column" => "route_id",
            "model" => "Quil\classes\page\models\QuilPageRoute",
            "inConstructor" => true,
            "as" => "route"
        ];
    }

    public function with(): array
    {
        return [
            "column" => "created_by",
            "model" => "admin\model\AdminModel",
            "inConstructor" => true,
            "as" => "admin"
        ];
    }

    public function hasMany(): array
    {
        return [

        ];
    }

}