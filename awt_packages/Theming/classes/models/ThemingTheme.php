<?php

namespace Theming\classes\models;

use model\interfaces\IRelationBelongs;
use model\Model;

class ThemingTheme extends Model
{
    public function __construct(int $id = null)
    {
        parent::__construct();
        $this->selectByID($id);
    }
}