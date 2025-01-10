<?php

namespace Theming\classes\events;

use event\interfaces\IEvent;
use event\interfaces\IEventListener;

class EThemePagesListener implements IEventListener
{

    public array $pages = [];

    public function handle(IEvent $event): array
    {
        if($event instanceof EGetThemePages) {
            $event->addPages($this->pages);
            return $event->bundle();
        }
        return [];
    }
}