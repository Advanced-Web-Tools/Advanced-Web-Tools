<?php
namespace Dashboard\classes\menu;

use Dashboard\classes\menu\MenuItem;


/**
 * Class DashboardMenu
 *
 * - Part of `Dashboard` package.
 *
 * Responsible for rendering navigation menu.
 */
final class DashboardMenu
{
    private array $menu;

    public function __construct()
    {
        $this->menu = [];
    }


    /**
     * Use to add menu item.
     *
     * @param \Dashboard\classes\menu\MenuItem $item
     * @return void
     */

    public function addItem(MenuItem $item): void
    {
        $this->menu[] = $item;
    }

    /**
     * Returns an array of `MenuItem` objects
     * @return array
     */
    public function getMenu(): array
    {
        return $this->menu;
    }

    /**
     * Render and prepare HTML of menus.
     *
     * @return string Combined html of each `MenuItem`.
     */
    public function getMenuHTML(): string
    {
        $result = "";

        foreach ($this->menu as $item) {
            $result .= $item->getHTML();
        }

        return $result;
    }

}