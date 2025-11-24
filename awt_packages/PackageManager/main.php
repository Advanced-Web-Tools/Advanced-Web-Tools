<?php

use packages\runtime\api\RuntimeLinkerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use PackageManager\event\MenuDrawListener;
final class PackageManager extends RuntimeLinkerAPI
{

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::WaitForPackage);
        $this->waitForRuntime("Dashboard");
    }

    public function setup(): void
    {
        $this->eventDispatcher->addListener("menu.request", new MenuDrawListener());
    }

    public function main(): void
    {
        $this->createLink("PackageManagerControllers", "/controllers/PackageManagerControllers.php");
        $this->createLink("PackageManagerRouter", "/routes/PackageManagerRouter.php");
    }
}