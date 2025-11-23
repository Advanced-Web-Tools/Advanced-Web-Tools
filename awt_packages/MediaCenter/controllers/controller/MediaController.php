<?php

use Dashboard\classes\dashboard\DashboardPage;
use MediaCenter\classes\MediaManager\MediaManager;
use view\View;

final class MediaController extends DashboardPage
{
    private MediaManager $manager;

    public function __construct()
    {
        parent::__construct();
        $this->manager = new MediaManager();
    }

    public function index(array|string $params): View
    {
        $this->adminCheck();
        $this->setTitle("Media Center");

        $this->eventDispatcher->dispatch($this->event);

        $this->manager->fetchContent();

        $bundle["mediaContent"] = $this->manager->objects;


        if (array_key_exists("filter", $params)) {
            $bundle["mediaContent"] = $this->manager->getOfType($params['filter']);
        }


        return $this->view($this->getViewBundle($bundle));
    }
}