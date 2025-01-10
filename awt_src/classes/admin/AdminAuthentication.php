<?php

namespace admin;

use database\DatabaseManager;
use session\SessionHandler;

/**
 * AdminAuthentication Class
 *
 * This class is responsible for authenticating an administrator
 * using their username and password. It extends the SessionHandler
 * to manage sessions upon successful authentication.
 */
final class AdminAuthentication extends SessionHandler
{
    private string $userName;
    private string $password;

    private DatabaseManager $database;

    /**
     * Constructor method for AdminAuthentication.
     *
     * Initializes the userName and password properties and creates
     * a new instance of DatabaseManager for database interactions.
     *
     * @param string $userName The username of the administrator.
     * @param string $password The password of the administrator.
     */
    public function __construct(string $userName, string $password)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->database = new DatabaseManager();
    }

    /**
     * Authenticates the administrator by verifying the username and
     * password against the database.
     *
     * The method hashes the password using SHA-512 and checks for a
     * matching record in the "awt_admin" table. If a match is found,
     * it initializes the session and stores the admins information.
     *
     * @return bool Returns true if authentication is successful,
     * false otherwise.
     */
    public function authenticate(): bool
    {
        $password_hash = hash('SHA512', $this->password);
        $result = $this->database->table("awt_admin")
            ->select()
            ->where([
                'username' => $this->userName,
                'password' => $password_hash,
            ])
            ->get();
        if(!empty($result)){
            $this->SessionHandler();
            $_SESSION['admin']['id'] = $result[0]['id'];
            $_SESSION['admin']['username'] = $result[0]['username'];
            $_SESSION['admin']['permission'] = $result[0]['permission_level'];
            $_SESSION['admin']['token'] = $result[0]['token'];


            $this->database->table("awt_admin")->where(["id" => $result[0]['id']])->update(["last_logged_ip" => $_SERVER['REMOTE_ADDR']]);

            return true;
        }

        return false;
    }

}