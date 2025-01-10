<?php

namespace Theming\classes\events;

use event\interfaces\IEvent;

class EGetThemePages implements IEvent
{

    private array $pages = [];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return "theming.pages.get";
    }

    public function addPages(array $pages): void
    {
        $this->pages = array_merge($this->pages, $pages);
    }

    /**
     * @inheritDoc
     */
    public function bundle(): array
    {
        return $this->pages;
    }
}