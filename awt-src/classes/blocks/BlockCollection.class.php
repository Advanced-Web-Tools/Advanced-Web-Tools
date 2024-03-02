<?php

namespace blocks;

class BlockCollection {
    public string $name;
    public string $description;
    public string $iconURL;
    public array $blocks;

    public function __construct(string $name = "Unnamed Collection", array $blocks = array(), string|null $iconURL = NULL, string|null $description = NULL) {
        $this->name = $name;
        $this->blocks = $blocks;
        $this->description = $description ?? "This is a $name collection.";
        $this->iconURL = $iconURL ?? "/";
    }

    public function getInfo() : array
    {
        $info = [
            "Name" => $this->name,
            "icon" => $this->iconURL,
            "description" => $this->description
        ];

        return $info;
    }

    public function addBlockToCollection(string $name, block $block) : void
    {
        $this->blocks[$name] = $block;
    }

    public function getAllBlocks() : array
    {
        return $this->blocks;
    }

    public function getBlockFromCollection(string $name) : string
    {
        return $this->blocks[$name]->getBlock();
    }

}