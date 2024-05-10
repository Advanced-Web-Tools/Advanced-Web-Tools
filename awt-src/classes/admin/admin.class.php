<?php

namespace admin;

use database\databaseConfig;
use notifications\notifications;
use session\sessionHandler;
use admin\profiler;
use DateTime;
use mail\mail;

class admin extends sessionHandler
{

    private databaseConfig $database;
    private object $mysqli;
    private profiler $profiler;

    public function signOut()
    {
        $this->sessionHandler();
        $this->sessionClearing();
    }

    public function createAccount(string $email, string $username, string $firstname, string $lastname, string $password, int $permission_level): string
    {

        $this->profiler = new profiler();

        if (!$this->profiler->checkPermissions($permission_level))
            return "Your account cannot create new account with permission level: $permission_level";

        if (
            $this->isStringEmptyOrSpaces($email) ||
            $this->isStringEmptyOrSpaces($username) ||
            $this->isStringEmptyOrSpaces($firstname) ||
            $this->isStringEmptyOrSpaces($lastname) ||
            $this->isStringEmptyOrSpaces($password)
        ) {
            return "All fields are required!";
        }

        if ($permission_level < 0 || $permission_level > 2) {
            return "Invalid permission level";
        }

        $invalidPassMsg = "Password must contain at least one uppercase character, at least one number and at least one special character!";

        if (strlen($password) < 8)
            return "Password must be at least 8 characters!";

        if (strlen($username) < 5)
            return "Username must be at least 5 characters!";

        if (trim($password) === trim($username))
            return "Password and username must differentiate!";

        if ($this->isPasswordGuessable($password))
            return "This password is blacklisted because it's very common and prone to attacks";

        if (!$this->passwordContainsNumbers($password))
            return $invalidPassMsg;

        if (!$this->passwordContainsSpecialCharacters($password))
            return $invalidPassMsg;

        if (!$this->passwordContainsUppercase($password))
            return $invalidPassMsg;

        $result = $this->getAccountByEmailOrUsername($email);

        $numRows = $result['num_rows'];

        if ($numRows > 0) {
            return "User with this email already exists!";
        }

        $result = $this->getAccountByEmailOrUsername($username);

        $numRows = $result['num_rows'];

        if ($numRows > 0) {
            return "User with this username already exists!";
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

        $notification = new notifications("Accounts", $this->profiler->name . " has created new account @$username", "high");
        $notification->pushNotification();

        return "Account created!";
    }

    private function connectToDatabase() : void
    {

        $this->database = new databaseConfig();

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();
    }

    private function isStringEmptyOrSpaces($str) : bool
    {
        if (empty($str)) {
            return true;
        }

        if (trim($str) == '') {
            return true;
        }

        return false;
    }

    private function passwordContainsNumbers($password) : int
    {
        return preg_match('/\d/', $password) === 1;
    }

    private function passwordContainsSpecialCharacters($password) : int
    {
        return preg_match('/[^A-Za-z0-9]/', $password) === 1;
    }

    private function passwordContainsUppercase($password) : int
    {
        return preg_match('/[A-Z]/', $password) === 1;
    }

    private function isPasswordGuessable($password)
    {

        $commonPasswords = [
            'password',
            'qwerty123',
            '123456789',
            'abcdefghi',
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

        $stmt = $this->mysqli->prepare("SELECT `id`, `username`, `firstname`, `email`, `lastname` FROM `awt_admin` WHERE `email` != ?;");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $result;
    }

    public function getAccountByEmailOrUsername(string $search): array
    {
        $this->connectToDatabase();

        $result = array();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_admin` WHERE `email` = ? OR `username` = ?;");
        $stmt->bind_param("ss", $search, $search);

        if ($stmt->execute()) {

            $stmt->store_result();

            $stmt->bind_result($result['id'], $result['email'], $result['username'], $result['firstname'], $result['lastname'], $result['last_logged_ip'], $result['password'], $result['token'], $result['permission_level']);
            $stmt->fetch();

            $numRows = $stmt->num_rows;

            $result['num_rows'] = $numRows;

            $stmt->close();

        } else {
            die($stmt->error);
        }

        return $result;
    }

    public function getAccountById(int $id): array
    {

        $this->connectToDatabase();

        $result = array();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_admin` WHERE `id` = ?");

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {

            $stmt->store_result();

            $stmt->bind_result($result['id'], $result['email'], $result['username'], $result['firstname'], $result['lastname'], $result['last_logged_ip'], $result['password'], $result['token'], $result['permission_level']);
            $stmt->fetch();

            $numRows = $stmt->num_rows;

            $result['num_rows'] = $numRows;

            $stmt->close();

        } else {
            die($stmt->error);
        }

        return $result;
    }

    public function changePassword(string $newPassword, int $id): bool
    {
        $this->connectToDatabase();

        $newPassword = hash("SHA512", $newPassword);

        $stmt = $this->mysqli->prepare("UPDATE `awt_admin` SET `password` = ? WHERE `id` = ?");
        $stmt->bind_param("si", $newPassword, $id);

        if ($stmt->execute()) {
            $stmt->close();

            $result = $this->getAccountById($id);

            $email = $result["email"];
            $fname = $result["firstname"];

            $content = "
            <h1 style='text-align: center;'>Password was changed</h1>
            <hr>
            <p>Hello " . $fname . ", </p>
            <p>Your password was changed. If you did not do this, please take immediate action by changing your password directly in database.</p>
            <p>Or you can go to <a href='" . HOSTNAME . "awt-admin/passwordreset.php' target='_blank' rel='noreferer'>this link</a> to request password reset link.</p>
            <p>If you want to change it manually in database, it is mandatory to use this hashing algorihtm: <b>SHA512</b></p>
            ";

            $mail = new mail(CONTACT_EMAIL, $email, "Password was changed", $content);

            $mail->sendMessage("Advanced Web Tools");

            return true;
        }
        return false;

    }

    public function deleteAccount(int $id)
    {
        $profiler = new profiler();
        if (!$profiler->checkPermissions(0))
            return "You need to be an admin to perform that operation!";

        $this->connectToDatabase();

        $stmt = $this->mysqli->prepare("DELETE FROM `awt_admin` WHERE `id` = ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $notification = new notifications("Accounts", $profiler->name . " has deleted account with ID: $id", "medium");
        $notification->pushNotification();

        return "Account deleted.";
    }

    public function updateInfo(int $id, string $fname, string $lname, string $email, null|string $password) : string
    {

        $auth = new authentication;

        if(!$auth->checkAuthentication()) die("ERROR: Not loged in!");

        $sql = "UPDATE `awt_admin` SET `firstname` = ? , `lastname` = ?, `email` = ?";


        if($password !== null){ 
            $sql .= ", `password` = ?";
        }

        $sql .= " WHERE `id` = ?;";


        $this->connectToDatabase();

        $stmt = $this->mysqli->prepare($sql);

        if($password !== null) {

            $invalidPassMsg = "Password must contain at least one uppercase character, at least one number and at least one special character!";

            if (strlen($password) < 8)
                return "Password must be at least 8 characters!";
    
            if ($this->isPasswordGuessable($password))
                return "This password is blacklisted because it's very common and prone to attacks";
    
            if (!$this->passwordContainsNumbers($password))
                return $invalidPassMsg;
    
            if (!$this->passwordContainsSpecialCharacters($password))
                return $invalidPassMsg;
    
            if (!$this->passwordContainsUppercase($password))
                return $invalidPassMsg;

            $password = hash("SHA512", $password);

            $stmt->bind_param("ssssi", $fname, $lname, $email, $password, $id);

        } else {
            $stmt->bind_param("sssi", $fname, $lname, $email, $id);
        }


        $stmt->execute();
        $stmt->close();

        return "Account info updated.";

    }
}
