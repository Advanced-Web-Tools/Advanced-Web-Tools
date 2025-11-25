<?php

namespace Quil\classes\sources\models;

use model\interfaces\IRelationBelongs;
use model\interfaces\IRelationWith;
use model\Model;

class QuilPageDataSource extends Model implements IRelationWith
{

    public int $id;
    public int $page_id;
    public int $table_id;
    public string $column_selector;
    public ?string $bind_param_url = null;
    public ?string $default_param_value = null;
    public ?string $table_name = null;
    public ?string $source_name;
    public ?array $table_columns;

    public function __construct(int $id)
    {
        parent::__construct();
        $this->selectByID($id);

        $this->table_name = $this->quil_table->name;

        foreach ($this->quil_table->cols as $col) {
            $this->columns[] = $col->name;
        }
    }

    public function with(): array
    {

        return [
            "column" => "table_id",
            "model" => "Quil\classes\sources\models\AwtTable",
            "inConstructor" => true,
            "as" => "quil_table"
        ];
    }
}