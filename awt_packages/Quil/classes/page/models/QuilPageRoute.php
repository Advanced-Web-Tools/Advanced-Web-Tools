<?php

namespace Quil\classes\page\models;

use model\interfaces\IRelationWith;
use model\Model;

/**
 * Class QuilPageRoute
 *
 * - Part of `Quil` package.
 * - Use with `Dashboard` package.
 * - A model.
 *
 * Manages custom page routes.
 */
class QuilPageRoute extends Model implements IRelationWith
{
    public string $route;
    public ?int $id;
    public int $created_by;

    public function __construct(null|array|int $data)
    {
        parent::__construct();

        if($data === null) {
            return;
        }

        if(!is_array($data)) {
            $this->selectByID($data);
            return;
        } else {
            $this->fromArray($data);
        }

        $this->model_source = "quil_page_route";
        $this->id_column = "id";
        $this->model_id = $data['id'];
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
}