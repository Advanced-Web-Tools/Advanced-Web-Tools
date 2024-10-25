<?php

namespace Quil\event;

use Dashboard\classes\menu\DashboardMenu;
use Dashboard\classes\menu\MenuItem;
use DOMException;
use event\interfaces\IEvent;
use event\interfaces\IEventListener;

final class MenuDrawListener implements IEventListener
{
    private DashboardMenu $menu;

    /**
     * @throws DOMException
     */
    private function addMenus(): void
    {
        $pageMenu = new MenuItem("Pages and Routes", "fa-solid fa-file-circle-plus", "/dashboard/pages");
        $pageMenu->setIconType("fa");
        $pageMenu->createDom();

        $this->menu->addItem($pageMenu);
    }


    public function handle(IEvent $event): array
    {
        $this->menu = $event->bundle()['menu'];
        $this->addMenus();
        return [];
    }
}