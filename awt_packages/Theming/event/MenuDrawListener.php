<?php

namespace Theming\event;

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
     * @var Admin Contains `Admin` object.
     */
    private Admin $admin;

    /**
     * @throws DOMException
     */
    private function addMenus(): void
    {
        if (!$this->admin->checkAuthentication() || !$this->admin->checkPermission(0)) {
            return;
        }

        $menu = new MenuItem("Customize", "fa-solid fa-brush", "/dashboard/themes");
        $menu->setIconType("fa");


        $builder = new MenuItem("Menu Builder", "fa-solid fa-bars", "/dashboard/menu_builder/");
        $builder->setIconType("fa");
        $builder->createDom();

        $menu->addChild($builder);
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