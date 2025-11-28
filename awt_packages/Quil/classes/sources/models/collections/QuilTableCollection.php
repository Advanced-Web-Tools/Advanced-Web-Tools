<?php

namespace Quil\classes\sources\models\collections;

use model\ModelCollection;
use Quil\classes\sources\models\AwtTable;

class QuilTableCollection extends ModelCollection
{
    public function getModel(): string
    {
        return AwtTable::class;
    }
}