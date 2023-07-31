<?php

namespace admin;

use database\databaseConfig;
use session\sessionHandler;
use admin\profiler;

class admin extends sessionHandler
{

    private object $database;
    private object $mysqli;
    private object $profiler;

    public function signOut()
    {
        $this->sessionHandler();
        $this->sessionClearing();
    }

    public function createAccount(string $email, string $username, string $firstname, string $lastname, string $password, string $permission_level)
    {

        $this->profiler = new profiler();

        if (!$this->profiler->checkPermissions($permission_level)) return "Your account cannot create new account with permission level: $permission_level";

        if (
            $this->isStringEmptyOrSpaces($email) ||
            $this->isStringEmptyOrSpaces($username) ||
            $this->isStringEmptyOrSpaces($firstname) ||
            $this->isStringEmptyOrSpaces($lastname) ||
            $this->isStringEmptyOrSpaces($password) ||
            $this->isStringEmptyOrSpaces($permission_level)
        ) {
            return "All fields must be filled!";
        }

        $invalidPassMsg = "Password must contain at least one uppercase character, at least one number and at least one special character!";

        if (strlen($password) < 8) return "Password must be at least 8 characters!";

        if (strlen($username) < 5) return "Username must be at least 5 characters!";

        if (trim($password) === trim($username)) return "Password and username must differentiate!";

        if ($this->isPasswordGuessable($password)) return "This password is blacklisted because it's very common and prone to attacks";

        if (!$this->passwordContainsNumbers($password)) return $invalidPassMsg;

        if (!$this->passwordContainsSpecialCharacters($password)) return $invalidPassMsg;

        if (!$this->passwordContainsUppercase($password)) return $invalidPassMsg;

        $this->connectToDatabase();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_admin` WHERE `email` = ? OR `username` = ?");

        $stmt->bind_param("ss", $email, $username);

        $stmt->execute();

        $result = $stmt->get_result();

        $numRows = $result->num_rows;

        $stmt->close();

        if ($numRows > 0) {
            return "User with this email or username already exists!";
        }


        $password = hash("SHA512", $password);

        $token = hash("SHA512", $password . time());

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_admin`(`email`, `username`, `firstname`, `lastname`, `last_logged_ip`, `password`, `token`, `permission_level`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("ssssssss", $email, $username, $firstname, $lastname, $_SERVER['REMOTE_ADDR'], $password, $token, $permission_level);

        $executionSuccess = $stmt->execute();

        if (!$executionSuccess) {
            $stmt->close();
            return "ERROR: Unknown error has occured please try again";
        }

        $stmt->close();

        return "Account created!";
    }

    private function connectToDatabase()
    {

        $this->database = new databaseConfig();

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();
    }

    private function isStringEmptyOrSpaces($str)
    {
        if (empty($str)) {
            return true;
        }

        if (trim($str) === '') {
            return true;
        }

        return false;
    }

    private function passwordContainsNumbers($password)
    {
        return preg_match('/\d/', $password) === 1;
    }

    private function passwordContainsSpecialCharacters($password)
    {
        return preg_match('/[^A-Za-z0-9]/', $password) === 1;
    }

    private function passwordContainsUppercase($password)
    {
        return preg_match('/[A-Z]/', $password) === 1;
    }

    private function isPasswordGuessable($password)
    {

        $commonPasswords = [
            'password',
            '123456',
            'qwerty',
            '123456789',
            'abc123',
        ];


        $lowercasePassword = strtolower($password);

        if (in_array($lowercasePassword, $commonPasswords)) {
            return true;
        }

        return false;
    }

    public function getAccountList()
    {
        $this->connectToDatabase();

        $this->profiler = new profiler();

        $email = $this->profiler->email;

        $stmt = $this->mysqli->prepare("SELECT `id`, `username`, `firstname`, `lastname` FROM `awt_admin` WHERE `email` != ?;");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $result;
    }

    public function deleteAccount(int $id)
    {
        $profiler = new profiler();
        if (!$profiler->checkPermissions(0)) return "You need to be an admin to perform that operation!";

        $this->connectToDatabase();

        $stmt = $this->mysqli->prepare("DELETE FROM `awt_admin` WHERE `id` = ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();

        return "Account deleted.";
    }
}
