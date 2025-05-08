<?php

use admin\Admin;
use Dashboard\event\MenuDrawListener;
use packages\runtime\api\RuntimeLinkerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class DashboardPackage extends RuntimeLinkerAPI
{

    final public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }

    final public function setup(): void
    {
        $listener = new MenuDrawListener();
        $this->eventDispatcher->addListener("menu.request", $listener);

        $this->createLink("DashboardController", "/controllers/DashboardController.php");
        $this->createLink("DashboardRoutes", "/routes/DashboardRoutes.php");
    }

    final public function main(): void
    {
        $this->addShared("Admin", (new Admin()));
    }
}