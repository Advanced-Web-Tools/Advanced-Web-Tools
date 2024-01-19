<?php

namespace blocks;

class blockOptions {
    public string $path;
    private string $body;
    public function __construct(string $path) {
        $this->path = $path;
        $this->loadOption();
    }

    private function loadOption() : void
    {
        if(file_exists($this->path)) {
            ob_start();
                include_once $this->path;
                $this->body = ob_get_contents();
            ob_end_clean();
        }
    }

    public function getoptions() : string
    {
        return $this->body;
    }

}