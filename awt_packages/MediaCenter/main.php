<?php

use MediaCenter\event\MenuDrawListener;
use packages\runtime\api\RuntimeLinkerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

final class MediaCenter extends RuntimeLinkerAPI
{
    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::WaitForPackage);
        $this->waitForRuntime("Quil");
    }

    public function setup(): void
    {

    }

    public function main(): void
    {
        $listener = new MenuDrawListener();
        $this->eventDispatcher->addListener("menu.request", $listener);
        $this->createLink("MediaCenterEditor", "/Quil/MediaCenterEditor.php");
        $this->createLink("MediaCenterController", "/controllers/MediaCenterController.php");
        $this->createLink("MediaRoutes", "/routes/MediaRoutes.php");
    }
}