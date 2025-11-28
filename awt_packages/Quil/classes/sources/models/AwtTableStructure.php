<?php

namespace Quil\classes\sources\models;

use model\Model;

class AwtTableStructure extends Model
{
    public function __construct(int $id)
    {
        parent::__construct();
        $this->selectByID($id, "awt_table_structure", "id");
    }
}