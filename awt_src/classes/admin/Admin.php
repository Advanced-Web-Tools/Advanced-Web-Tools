<?php

namespace admin;

use admin\model\AdminModel;

/**
 * Admin Class
 *
 * This class extends the AdminModel to provide functionality related
 * to administrator permissions, authentication, and session management.
 */
class Admin extends AdminModel
{
    /**
     * Constructor method for Admin.
     *
     * Calls the parent constructor from AdminModel to initialize
     * the admin model.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Checks if the administrator has permission at a specified level.
     *
     * Compares the current administrator's permission level with the
     * provided level and returns true if the current level is less than
     * or equal to the specified level.
     *
     * @param int $level The permission level to check against.
     * @return bool Returns true if the administrator has the required
     * permission level, false otherwise.
     */
    public function checkPermission(int $level): bool
    {
        if($this->getParam("permission_level") <= $level) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the administrator is authenticated based on the session token.
     *
     * This method ensures that a session is started and verifies that
     * the stored token matches the token in the session. Returns true
     * if authenticated, false otherwise.
     *
     * @return bool Returns true if the administrator is authenticated,
     * false otherwise.
     */
    public function checkAuthentication(): bool
    {
        if(!isset($_SESSION)) {
            session_start();
        }

        if($this->token === null) {
            return false;
        }

        if($this->token !== $_SESSION['admin']['token']) {
            return false;
        }

        return true;
    }

    /**
     * Logs out the administrator by clearing the session.
     *
     * This method unsets the admin session to effectively log the
     * administrator out.
     *
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['admin']);
    }

}