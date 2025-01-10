<?php

namespace Quil\classes\editor\event;

use event\interfaces\IEvent;
use event\interfaces\IEventListener;

class EQuilEditorListener implements IEventListener
{
    private EQuilEditor $editor;
    private array $paths;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * @inheritDoc
     */
    public function handle(IEvent $event): array
    {
        if($event instanceof EQuilEditor) {
            $this->editor = $event->bundle()["e"];
            $this->editor->addScripts($this->paths);
            return [];
        } else {
            die("Quil: Wrong event type provided.");
        }
    }
}