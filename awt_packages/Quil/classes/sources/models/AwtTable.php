<?php

namespace Quil\classes\sources\models;

use model\interfaces\IRelationHasMany;
use model\Model;

class AwtTable extends Model implements IRelationHasMany
{
    public function __construct(?int $id)
    {
        parent::__construct();
        $this->selectByID($id, "awt_table", "id");
    }

    public function hasMany(): array
    {
        return [
            "column" => "table_id",
            "model" => ["Quil\classes\sources\models\AwtTableStructure"],
            "as" => "cols",
            "inConstructor" => true
        ];
    }

}