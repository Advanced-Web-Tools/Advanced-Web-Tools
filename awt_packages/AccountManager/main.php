<?php

use AccountManager\event\MenuDrawListener;
use packages\runtime\api\RuntimeLinkerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class AccountManager extends RuntimeLinkerAPI
{

    final public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::WaitForPackage);
        $this->waitForRuntime("Dashboard");
    }

    /**
     * @inheritDoc
     */
    public function setup(): void
    {
        $listener = new MenuDrawListener();
        $this->eventDispatcher->addListener("menu.request", $listener);
    }

    /**
     * @inheritDoc
     */
    public function main(): void
    {
        $this->createLink("AccountManagerController", "/controllers/AccountManagerController.php");
        $this->createLink("AccountManagerRouter", "/routes/AccountManagerRouter.php");
    }
}