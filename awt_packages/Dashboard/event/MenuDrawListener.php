<?php

namespace Dashboard\event;

use admin\Admin;
use Dashboard\classes\menu\DashboardMenu;
use Dashboard\classes\menu\MenuItem;
use DOMException;
use event\interfaces\IEvent;
use event\interfaces\IEventListener;

/**
 * Class MenuDrawListener
 *
 * - Part of DashboardPackage
 *
 * Use to add navigation to DashboardMenu
 *
 * Use exclusively for `menu.request` event.
 *
 */
class MenuDrawListener implements IEventListener
{
    /**
     * @var DashboardMenu Contains `DashboardMenu` object.
     *
     * Use `addItem()` method to add your `MenuItem` object.
     */
    public DashboardMenu $dashboardMenu;

    /**
     * @throws DOMException
     */
    private function addMenus(): void
    {
        $admin = new Admin();


        if(!$admin->checkAuthentication() || !$admin->checkPermission(2)) {
            return;
        }

        $menu = new MenuItem("Dashboard", "fa-solid fa-house", "/dashboard");
        $menu->setIconType("fa");
        $menu->createDom();
        $this->dashboardMenu->addItem($menu);
    }


    public function handle(IEvent $event): array
    {
        $this->dashboardMenu = $event->bundle()['menu'];

        try {
            $this->addMenus();
        } catch (DOMException $e) {
            die($e->getMessage());
        }

        return [];
    }
}