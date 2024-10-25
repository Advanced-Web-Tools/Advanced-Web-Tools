<?php

namespace MediaCenter\event;

use Dashboard\classes\menu\DashboardMenu;
use Dashboard\classes\menu\MenuItem;
use DOMException;
use event\interfaces\IEvent;
use event\interfaces\IEventListener;

final class MenuDrawListener implements IEventListener
{
    public DashboardMenu $dashboardMenu;


    /**
     * @throws DOMException
     */
    private function addMenus(): void
    {
        $item = new MenuItem("Media Center", "fa-solid fa-photo-film", "/dashboard/media");
        $item->setIconType("fa");

        $image = new MenuItem("Images", "fa-solid fa-image", "/dashboard/media/images");
        $image->setIconType("fa");

        $video = new MenuItem("Videos", "fa-solid fa-film", "/dashboard/media/videos");
        $video->setIconType("fa");

        $audio = new MenuItem("Audio", "fa-solid fa-record-vinyl", "/dashboard/media/audio");
        $audio->setIconType("fa");

        $document = new MenuItem("Documents", "fa-solid fa-file-pdf", "/dashboard/media/documents");
        $document->setIconType("fa");

        $item->addChild($image);
        $item->addChild($video);
        $item->addChild($audio);
        $item->addChild($document);
        $item->createDom();
        $this->dashboardMenu->addItem($item);
    }

    public function handle(IEvent $event): array
    {
        $this->dashboardMenu = $event->bundle()['menu'];

        $this->addMenus();


        return [];
    }
}