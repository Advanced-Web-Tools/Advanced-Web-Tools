<?php

namespace model\interfaces;

interface IRelationWith
{
    /**
     * Return an array with parameters:
     * `[
     *      "column" => "example",
     *      "model" => namespace/Model,
     *      "inConstructor" => true
     * ]`
     *
     * Where `column` represents a foreign key in your table.
     * `inConstructor` optional tells model to pass id from column directly into constructor.
     *
     * @return array
     */
    public function with(): array;
}