<?php

namespace 
Dashboard\classes\dashboard;

use admin\Admin;
use controller\Controller;
use 
Dashboard\classes\menu\DashboardMenu;
use 
Dashboard\classes\menu\event\DashboardMenuRequest;
use view\View;

/**
 * Class DashboardPage
 *
 *  - Part of Dashboard package
 *
 *  Extend your controller that contains Dashboard screens (pages), for easier handling of menu
 * creation and user authentication.
 *
 */
abstract class DashboardPage extends Controller
{
    /**
     *  Title of the page.
     */
    public string $title;

    /**
     * @var DashboardMenu
     * Contains `DashboardMenu` object.
     */
    public DashboardMenu $dashboardMenu;

    /**
     * @var DashboardMenuRequest
     * Contains `DashboardMenuRequest` event.
     */
    public DashboardMenuRequest $event;

    /**
     * @var Admin
     * Contains `Admin` object.
     *
     *
     *  Use to gather information of logged-in user.
     */
    public Admin $admin;


    /**
     * Handles menu creation, event dispatching, authentication.
     */
    public function __construct()
    {
        $this->title = "Dashboard";
        $this->event = new DashboardMenuRequest();
        $this->dashboardMenu = new DashboardMenu();

        $this->admin = new Admin();
        $this->event->dashboardMenu = $this->dashboardMenu;

        $this->adminCheck();
    }

    abstract public function index(array|string $params): View;

    private function adminCheck(): void
    {
        if(str_starts_with($_SERVER["REQUEST_URI"], "/dashboard/login/")) {
            return;
        }


        if(str_starts_with($_SERVER["REQUEST_URI"], "/dashboard/loginAction")) {
            return;
        }

        if (!$this->admin->checkAuthentication()) {
            header("Location: /dashboard/login/failed");
            exit();
        }
    }

    /**
     * Sets page title.
     * @param string $title
     * @return void
     */
    final protected function setTitle(string $title): void
    {
        $this->title = $title;
    }


    /**
     * Merges `DashboardPage` local bundle with yours (if available).
     * @param array $combine Your custom data set (bundle).
     * @return array Returns combined bundle.
     */
    final protected function getViewBundle(array $combine = []): array
    {
        $arr = ["title" => $this->title, "admin" => $this->admin, "navigation" => $this->dashboardMenu->getMenuHTML()];

        return array_merge($arr, $combine);
    }

}