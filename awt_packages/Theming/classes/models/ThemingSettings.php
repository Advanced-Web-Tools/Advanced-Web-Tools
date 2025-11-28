<?php

namespace Theming\classes\models;

use model\Model;

class ThemingSettings extends Model
{
    public function __construct(int $id = null)
    {
        parent::__construct();
        $this->selectByID($id);
    }
}