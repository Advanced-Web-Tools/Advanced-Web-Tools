<?php

namespace Quil\classes\sources\models;

use model\Model;

class DummySourceModel extends Model
{

    public string $dummyName;

    public function __construct(mixed $value, string $table = '', string $column = '', string $name = '')
    {
        parent::__construct();

        $this->dummyName = $name;

        if($table == '' || $column == '')
            return;

        $res = $this->table($table)->select(["*"])->where([$column => $value])->get()[0];
        if ($res) {
            $this->fromArray($res);
        }
    }
}