<?php

use packages\runtime\api\RuntimeLinkerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use Quil\event\MenuDrawListener;

final class Quil extends RuntimeLinkerAPI
{

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }

    public function setup(): void
    {
        $listener = new MenuDrawListener();
        $this->eventDispatcher->addListener("menu.request", $listener);
        $this->createLink("QuilControllers", "/controllers/QuilControllers.php");
        $this->createLink("QuilRouter", "/routes/QuilRouter.php");
    }

    public function main(): void
    {

    }
}