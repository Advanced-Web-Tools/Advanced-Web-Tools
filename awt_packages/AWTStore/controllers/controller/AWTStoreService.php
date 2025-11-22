<?php

use Dashboard\classes\dashboard\DashboardPage;
use redirect\Redirect;
use view\View;

class AWTStoreService extends DashboardPage
{

    public function index(array|string $params): View
    {
        return $this->view($params);
    }

    public function install(array|string $params): Redirect
    {
        return (new Redirect())->back();
    }
}