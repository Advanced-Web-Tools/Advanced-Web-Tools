<?php

namespace router\events;

use event\interfaces\IEvent;
use event\interfaces\IEventListener;
use router\manager\RouterManager;

final class EDynamicRouteListener implements IEventListener
{

    private RouterManager $routerManager;

    public function addManager(?RouterManager $routerManager): void
    {
        if($routerManager === null) {
            $this->routerManager = new RouterManager();
        } else {
            $this->routerManager = $routerManager;
        }
    }
    public function handle(IEvent $event): array
    {
        $this->routerManager->loadRouters($event->bundle());
        return [];
    }
}