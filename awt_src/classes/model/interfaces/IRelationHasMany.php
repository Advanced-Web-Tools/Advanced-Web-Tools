<?php

namespace model\interfaces;

interface IRelationHasMany
{
    /**
     * Return an array with parameters:
     * `[
     *      "column" => "example",
     *      "model" => [namespace\Model, namespace\Model2],
     * ]`
     *
     * Where `column` represents a key in your table.
     *
     * @return array
     */
    public function hasMany(): array;
}