<?php

namespace
Dashboard\classes\menu\event;

use Dashboard\classes\menu\DashboardMenu;
use event\interfaces\IEvent;

/**
 * Class DashboardMenuRequest
 *
 * - Part of `Dashboard` package.
 *
 * Requests menu items via event from other packages.
 */
class DashboardMenuRequest implements IEvent
{

    public DashboardMenu $dashboardMenu;

    public function getName(): string
    {
        return "menu.request";
    }

    public function bundle(): array
    {
        global $shared;
        return ["menu" => $this->dashboardMenu, "admin" => $shared["Dashboard"]["Admin"]];
    }
}