<?php

/**
 * Handles the authentication process for an administrator user.
 * This class is responsible for verifying administrator credentials,
 * initializing sessions, and maintaining session data after successful authentication.
 *
 * Extends the SessionHandler for session management functionality.
 */

namespace admin;

use /**
 * Class DatabaseManager
 *
 * Manages database connections, queries, and transactions. Provides
 * functionality to interact with the database in a structured and
 * organized manner.
 *
 * Responsibilities:
 * - Establishing and managing connections to the database.
 * - Executing database queries.
 * - Managing transactions (commit, rollback).
 * - Handling database errors and exceptions.
 * - Providing utilities for prepared statements and parameter binding.
 */
    database\DatabaseManager;
use /**
 * Class SessionHandler
 *
 * Handles session management operations, including reading, writing, updating,
 * and destroying session data. Implements session handling according to PHP's
 * session handler interface to allow custom session storage.
 *
 * Responsibilities:
 * - Read session data from the storage.
 * - Write updated session data to the storage.
 * - Delete session data upon session expiration or destruction.
 * - Manage session lifecycle.
 *
 * Usage:
 * This handler can be registered using session_set_save_handler() for
 * custom session handling mechanisms.
 */
    session\SessionHandler;

/**
 * Handles administrative authentication within the system.
 *
 * The AdminAuthentication class extends the functionality of the
 * SessionHandler class and is responsible for verifying administrator
 * credentials and managing authenticated admin sessions.
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