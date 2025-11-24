<?php

namespace AWTStore\source\event\menu;

use admin\Admin;
use Dashboard\classes\menu\MenuItem;
use DOMException;
use event\interfaces\IEvent;
use event\interfaces\IEventListener;
use Dashboard\classes\menu\DashboardMenu;

class StoreMenu implements IEventListener
{

    /**
     * @inheritDoc
     */


    public DashboardMenu $dashboardMenu;

    private Admin $admin;


    /**
     * @throws DOMException
     */
    private function AddMenus(): void
    {
        if(!$this->admin->checkAuthentication() || !$this->admin->checkPermission(0))
            return;

        $menu = new MenuItem("Marketplace", "fa-solid fa-box", "/dashboard/store");
        $menu->setIconType("fa");

        $menu->createDom();
        $this->dashboardMenu->addItem($menu);
    }


    public function handle(IEvent $event): array
    {
        $this->dashboardMenu = $event->bundle()["menu"];
        $this->admin = $event->bundle()["admin"];

        try {
            $this->AddMenus();
        } catch (DOMException $e) {
            die($e->getMessage());
        }

        return [];
    }
}