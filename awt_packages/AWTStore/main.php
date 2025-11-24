<?php

use AWTStore\source\event\menu\StoreMenu;
use packages\runtime\api\RuntimeLinkerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class AWTStore extends RuntimeLinkerAPI
{

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::WaitForPackage);
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->waitForRuntime("Dashboard");
    }

    public function setup(): void
    {

    }

    public function main(): void
    {
        $menuStore = new StoreMenu();

        $this->eventDispatcher->addListener("menu.request", $menuStore);

        $this->createLink("AWTStoreControllerAPI", "/controllers/AWTStoreControllerAPI.php");
        $this->createLink("AWTStoreRouter", "/routes/AWTStoreRouter.php");
    }
}