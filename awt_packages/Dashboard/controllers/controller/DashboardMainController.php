<?php

use Dashboard\classes\dashboard\DashboardPage;
use redirect\Redirect;
use view\View;

final class DashboardMainController extends DashboardPage
{

    public function index(array|string $params = null): View
    {
        $this->adminCheck();
        $this->setTitle("Dashboard");

        $this->eventDispatcher->dispatch($this->event);
        return $this->view($this->getViewBundle(["paths" => $locs]));
    }

    public function login(array|string $params): View
    {
        return $this->view(['status' => $params['status']]);
    }

}