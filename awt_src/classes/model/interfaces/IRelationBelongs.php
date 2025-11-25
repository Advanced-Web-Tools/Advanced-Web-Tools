<?php

namespace model\interfaces;
use model\Model;

interface IRelationBelongs
{
    public function belongsTo(): Model;
}