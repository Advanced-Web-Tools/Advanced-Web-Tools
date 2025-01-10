<?php

use AccountManager\classes\AccountManager;
use admin\Admin;
use admin\model\AdminModel;
use Dashboard\classes\dashboard\DashboardPage;
use redirect\Redirect;
use view\View;

final class AccountController extends DashboardPage
{

    public function index(array|string $params): View
    {
        $this->adminCheck();

        if (!$this->admin->checkPermission(2)) {
            $red = new Redirect();
            $red->back();
            header("Location: {$red->getRedirectTo()}");
            exit();
        }

        $bundle = $params;

        $accountManager = new AccountManager();

        $bundle["accounts"] = $accountManager->fetchAccounts()->getAccounts();
        if ($params["id"] != null) {
            $adminModel = new AdminModel((int)$params['id']);
            if ($adminModel->username !== null) {
                $bundle["profile"] = $adminModel;
            }
        } else {
            $bundle["profile"] = $this->admin;
        }

        $this->setTitle("Accounts");
        $this->eventDispatcher->dispatch($this->event);
        return $this->view($this->getViewBundle($bundle));
    }

    public function create(array|string $params): Redirect
    {
        $this->adminCheck();
        $red = new Redirect();
        if (!$this->admin->checkPermission(1)) {
            return $red->back();
        }

        if($_POST["password1"] !== $_POST["password2"]) {
            $red->back();
            $red->redirectTo = explode("?", $red->redirectTo)[0];
            $red->redirectTo = $red->redirectTo . "?creation=Passwords do not match";
            return $red;
        }

        $accountManager = new AccountManager();
        $accountManager->createAccount($_POST["username"], $_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["password1"], "awt_data/media/icons/circle-user-regular.svg", $_POST["perm_level"]);
        $red->back();
        $red->redirectTo = explode("?", $red->redirectTo)[0];
        $red->redirectTo = $red->redirectTo . "?creation=Created account";
        return $red;
    }

    public function delete(array|string $params): Redirect
    {
        $this->adminCheck();
        if ($this->admin->checkPermission(2)) {
            $accountManager = new AccountManager();
            if ($params["id"] != null) {
                $accountManager->fetchAccounts();
                if ($accountManager->accounts[$params["id"]]->permission_level >= $this->admin->permission_level) {
                    if (count($accountManager->accounts) > 1) {
                        $accountManager->deleteAccount($params["id"]);
                    } else {
                        $red = new Redirect();
                        return $red->back();
                    }
                }
            }
        } else {
            $red = new Redirect();
            return $red->back();
        }
        $red = new Redirect();
        return $red->redirect("/dashboard/accounts/");
    }

    public function changePassword(array|string $params): Redirect
    {
        $this->adminCheck();
        $admin = new Admin();

        $red = new Redirect();

        if (trim($_POST["new_password"]) != "" && strlen(trim($_POST["new_password"])) >= 8) {
            $status = $admin->changePassword($_POST["current_password"], $_POST["new_password"]);
        } else {
            $status = "Password cannot be empty or less than 5 characters";
        }

        $red->back();
        $red->redirectTo = explode("?", $red->redirectTo)[0];
        $red->redirectTo = $red->redirectTo . "?password_changed=$status";

        return $red;
    }

    public function changeEmail(array|string $params): Redirect
    {
        $this->adminCheck();
        $admin = new Admin();

        $red = new Redirect();

        $email = $_POST["email"];
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $admin->setEmail($email);
            $admin->save();
            $status = "Email changed";
        } else {
            $status = "Invalid email";
        }
        $red->back();

        $red->redirectTo = explode("?", $red->redirectTo)[0];

        $red->redirectTo = $red->redirectTo . "?email_changed=$status";

        return $red;
    }

}