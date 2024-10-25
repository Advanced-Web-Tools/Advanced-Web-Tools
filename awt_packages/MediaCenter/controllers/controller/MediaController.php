<?php

use Dashboard\classes\dashboard\DashboardPage;
use MediaCenter\classes\MediaManager\MediaCenterAlbumsManager;
use MediaCenter\classes\MediaManager\MediaManager;
use view\View;

final class MediaController extends DashboardPage
{
    private MediaCenterAlbumsManager $albumsManager;
    private MediaManager $manager;
    public function __construct()
    {
        parent::__construct();
        $this->albumsManager = new MediaCenterAlbumsManager();
        $this->manager = new MediaManager();
    }

    public function index(array|string $params): View
    {
        $this->setTitle("Media Center");

        $this->eventDispatcher->dispatch($this->event);

        return $this->view($this->getViewBundle(["media" => $this->manager->content]));
    }
}