<?php

use packages\runtime\api\RuntimeLinkerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use Settings\event\MenuDrawListener;

final class AWTSettings extends RuntimeLinkerAPI
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
    }

    public function main(): void
    {
        $this->createLink("SettingsControllerAPI", "/controllers/SettingsControllerAPI.php");
        $this->createLink("SettingsRouter", "/routes/SettingsRouter.php");
    }
}