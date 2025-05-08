<?php

namespace PackageManager\event;

use admin\Admin;
use Dashboard\classes\menu\DashboardMenu;
use Dashboard\classes\menu\MenuItem;
use DOMException;
use event\interfaces\IEvent;
use event\interfaces\IEventListener;

final class MenuDrawListener implements IEventListener
{
    public DashboardMenu $dashboardMenu;

    private Admin $admin;

    /**
     * @throws DOMException
     */
    private function addMenus(): void
    {
        if(!$this->admin->checkAuthentication() || !$this->admin->checkPermission(1)) {
            return;
        }

        $item = new MenuItem("Package Manager", "fa-solid fa-boxes-packing", "/dashboard/package_manager/");
        $item->setIconType("fa");

        $plugins = new MenuItem("Plugins", "fa-solid fa-puzzle-piece", "/dashboard/package_manager/plugins");
        $plugins->setIconType("fa");

        $themes = new MenuItem("Themes", "fa-solid fa-paint-roller", "/dashboard/package_manager/themes");
        $themes->setIconType("fa");

        $system = new MenuItem("System Packages", "fa-solid fa-gears", "/dashboard/package_manager/system");
        $system->setIconType("fa");

        $item->addChild($plugins);
        $item->addChild($themes);
        $item->addChild($system);
        $item->createDom();
        $this->dashboardMenu->addItem($item);
    }

    /**
     * @throws DOMException
     */
    public function handle(IEvent $event): array
    {
        $this->dashboardMenu = $event->bundle()['menu'];
        $this->admin = $event->bundle()['admin'];

        $this->addMenus();


        return [];
    }
}