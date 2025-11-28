<?php

namespace Quil\classes\page\models;

use model\interfaces\IRelationBelongs;
use model\interfaces\IRelationWith;
use model\Model;

/**
 * Class PageContent
 *
 * - Part of `Quil` package.
 * - Use with `Dashboard` package.
 *
 * Manages content of custom pages.
 * @property string $content
 */
class QuilPageContent extends Model implements IRelationBelongs
{
    public function __construct(?int $id)
    {
        parent::__construct($id);
        $this->selectByID($id);
    }

    public function belongsTo(): array
    {
        return [
          "column" => "id",
          "model" => "Quil\classes\page\models\QuilPage",
          "inConstructor" => true,
          "as" => "page"
        ];
    }
}