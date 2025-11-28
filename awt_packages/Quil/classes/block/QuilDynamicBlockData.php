<?php

namespace Quil\classes\block;

class QuilDynamicBlockData
{
    public string $belongs;

    public string $key;
    public string $placeHolder;

    public function __construct(string $key, string $placeHolder)
    {
        $this->key = $key;
        $this->placeHolder = $placeHolder;
    }

}