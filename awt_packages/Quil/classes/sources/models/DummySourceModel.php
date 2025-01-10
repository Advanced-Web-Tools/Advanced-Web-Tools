<?php

namespace Quil\classes\sources\models;

use model\Model;

class DummySourceModel extends Model
{
    public function __construct(mixed $value, string $table = '', string $column = '')
    {
        parent::__construct();
        $res = $this->table($table)->select(["*"])->where([$column => $value])->get()[0];
        if ($res) {
            $this->createFromArray($res);
        }
    }

    private function createFromArray(array $data): void {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}