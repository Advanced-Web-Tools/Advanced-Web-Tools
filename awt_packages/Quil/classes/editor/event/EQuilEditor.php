<?php

namespace Quil\classes\editor\event;

use event\interfaces\IEvent;

class EQuilEditor implements IEvent
{

    private array $scriptPaths = [];

    public function addScripts(array $paths): void
    {
        $this->scriptPaths = array_merge($this->scriptPaths, $paths);
    }

    public function retrieveScripts(): array
    {
        return $this->scriptPaths;
    }

    public function getName(): string
    {
        return "quil.editor.request";
    }

    public function bundle(): array
    {
        return ["e" => $this];
    }
}