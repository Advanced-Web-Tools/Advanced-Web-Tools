<?php

use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
use router\Router;

final class AccountManagerRouter extends RuntimeRouterAPI
{
    private RuntimeControllerAPI $controller;

    /**
     * @inheritDoc
     */
    public function setup(): void
    {
        $this->controller = $this->getPassable($this->name, "AccountManagerController");
    }

    /**
     * @inheritDoc
     */
    public function main(): void
    {
        $this->addRouter(new Router("/dashboard/accounts/{id}", "index" , $this->controller->getController("AccountController")));
        $this->addRouter(new Router("/dashboard/account_manager/delete/{id}", "delete" , $this->controller->getController("AccountController")));
        $this->addRouter(new Router("/dashboard/account_manager/change_password", "changePassword" , $this->controller->getController("AccountController")));
        $this->addRouter(new Router("/dashboard/account_manager/change_email", "changeEmail" , $this->controller->getController("AccountController")));
        $this->addRouter(new Router("/dashboard/account_manager/create", "create" , $this->controller->getController("AccountController")));
    }
}