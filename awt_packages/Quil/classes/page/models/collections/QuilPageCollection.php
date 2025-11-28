<?php

namespace Quil\classes\page\models\collections;

use model\ModelCollection;

class QuilPageCollection extends ModelCollection
{
    public function getModel(): string
    {
        return "Quil\classes\page\models\QuilPage";
    }
}