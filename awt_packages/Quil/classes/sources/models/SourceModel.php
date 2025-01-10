<?php

namespace Quil\classes\sources\models;

use model\Model;

class SourceModel extends Model
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
        $this->selectByID($id, 'quil_page_data_source');

        $this->table_name = $this->table('awt_table')
            ->select(['*'])
            ->where(['id' => $this->table_id])
            ->get()[0]['name'];

        $this->columns = $this->table("awt_table_structure")->select(["*"])->where(['table_id' => $this->table_id])->get();

    }
}