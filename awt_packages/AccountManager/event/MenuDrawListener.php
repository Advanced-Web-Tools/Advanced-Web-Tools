<?php

namespace AccountManager\event;

use admin\Admin;
use Dashboard\classes\menu\DashboardMenu;
use Dashboard\classes\menu\MenuItem;
use DOMException;
use event\interfaces\IEvent;
use event\interfaces\IEventListener;

class MenuDrawListener implements IEventListener
{

    public DashboardMenu $dashboardMenu;
    private Admin $admin;

    /**
     * @throws DOMException
     */
    private function addMenus(): void
    {
        if (!$this->admin->checkAuthentication() || !$this->admin->checkPermission(2)) {
            return;
        }

        $menu = new MenuItem("Account Manager", "fa-solid fa-users", "/dashboard/accounts/");
        $menu->setIconType("fa");
        $menu->createDom();
        $this->dashboardMenu->addItem($menu);
    }

    public function handle(IEvent $event): array
    {
        $this->dashboardMenu = $event->bundle()['menu'];
        $this->admin = $event->bundle()['admin'];

        try {
            $this->addMenus();
        } catch (DOMException $e) {
            die($e->getMessage());
        }

        return [];
    }
}