<?php

use admin\Admin;
use admin\AdminAuthentication;
use controller\Controller;
use JetBrains\PhpStorm\NoReturn;
use redirect\Redirect;
use view\View;

final class DashboardActionController extends Controller
{
    private Redirect $redirect;

    public function __construct()
    {
        $this->redirect = new Redirect();
    }


    public function index(array|string $params): Redirect|View
    {
        // TODO: Implement index() method.
        return $this->redirect->back();
    }

    #[NoReturn] public function loginAction(array|string $params): Redirect
    {
        $admin = new Admin();
        if (!$admin->checkAuthentication()) {
            $auth = new AdminAuthentication($_POST["username"], $_POST["password"]);
            if ($auth->authenticate()) {
                $this->redirect->redirect("/dashboard");
            } else {
                $this->redirect->redirect("/dashboard/login/failed");
            }
        } else {
            $this->redirect->redirect("/dashboard");
        }
        return $this->redirect;
    }

    #[NoReturn] public function logoutAction(array|string $params): Redirect
    {
        $admin = new Admin();
        $admin->logout();
        return $this->redirect->redirect("/login/logout");
    }

}