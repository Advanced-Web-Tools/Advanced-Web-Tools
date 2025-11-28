<?php

namespace Theming\classes\models;
use model\interfaces\IRelationBelongs;
use model\Model;
class ThemingCustomPage extends Model implements IRelationBelongs
{

    public function __construct(int $id = null)
    {
        parent::__construct();
        $this->selectByID($id);
    }

    public function belongsTo(): array
    {
        return ["column" => "theme_id", "model" => "Theming\classes\models\ThemingTheme", "inConstructor" => true];
    }
}