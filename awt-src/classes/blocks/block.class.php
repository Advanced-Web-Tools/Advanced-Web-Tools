<?php

namespace blocks;

class block {
    public string $name;
    public string $iconURL;
    public string $description;
    public string $descriptionImageURL;
    public string $path;
    private string $body;
    public function __construct(string $name, string $path, string|null $iconURL = NULL, string|null $description = NULL) {
        $this->name = $name;
        $this->path = $path;
        $this->iconURL = $iconURL ?? "/";
        $this->description = $description ?? "This is a $name block.";
        $this->loadBlock();
    }

    public function getBlockInfo() : array
    {
        return array(
            "Name" => $this->name,
            "icon" => $this->iconURL,
            "description" => $this->description
        );
    }

    private function loadBlock() : void
    {   
        if(file_exists($this->path)) {
            ob_start();
                include_once $this->path;
            $this->body = ob_get_contents();
            ob_end_clean();
        }
    }

    public function getBlock() : string
    {
        return $this->body;
    }
}
