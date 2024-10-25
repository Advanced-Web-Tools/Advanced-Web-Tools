<?php

namespace render\events;

use event\interfaces\IEvent;
use render\Render;

/**
 * RenderReadyEvent class
 *
 * It is used to signal when the rendering process is complete,
 * providing access to the renderer instance that handled the render.
 */
class RenderReadyEvent implements IEvent
{
    /**
     * @var Render $renderer
     * Stores an instance of the Render class that executed the rendering.
     */
    public Render $renderer;


    public function getName(): string
    {
        return "renderer.ready";
    }

    public function bundle(): array
    {
        return ["renderer" => $this->renderer];
    }
}