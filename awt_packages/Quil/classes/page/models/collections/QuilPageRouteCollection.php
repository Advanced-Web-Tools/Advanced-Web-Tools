<?php

namespace Quil\classes\page\models\collections;

use model\ModelCollection;
use Quil\classes\page\models\QuilPageRoute;

class QuilPageRouteCollection extends ModelCollection
{
    public function getModel(): string
    {
        return "Quil\classes\page\models\QuilPageRoute";
    }
}