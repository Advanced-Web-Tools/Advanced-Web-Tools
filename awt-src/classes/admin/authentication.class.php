<?php

namespace admin;
use database\databaseConfig;
use session\sessionHandler;

class authentication extends sessionHandler
{
    protected string $loginInfo;
    protected string $password;
    private array $retrieved;
    protected object $database;
    private object $mysqli;
    private object $stmt;

    public function __construct()
    {
        $this->database = new databaseConfig;
        $this->database->checkAuthority();
        $this->mysqli = $this->database->getConfig();
    }

    public function authenticateUser($login, $password)
    {
        $this->loginInfo = $login;
        $this->password = $password;
        $this->stmt = $this->mysqli->prepare('SELECT * FROM `awt_admin` WHERE `username` = ? OR `email` = ?;');
        $this->stmt->bind_param('ss', $this->loginInfo, $this->loginInfo);
        $this->stmt->execute();
        $this->stmt->store_result();
        $this->stmt->bind_result($this->retrieved['id'], $this->retrieved['email'], $this->retrieved['username'], $this->retrieved['firstname'], $this->retrieved['lastname'], $this->retrieved['lastIp'], $this->retrieved['password'], $this->retrieved['token'], $this->retrieved['permission']);
        $this->stmt->fetch();
        
        if($this->stmt->num_rows != 1) {
            return 'false';
        }
        
        $this->stmt->close();

        if(hash('SHA512', $this->password) != $this->retrieved['password']) {
            return false;
        }

        $this->sessionHandler();

        $_SESSION['userInfo'] = $this->retrieved;
        $_SESSION['sessionInfo']['expires'] = time() + 1800;
        $_SESSION['sessionInfo']['bind_to_ip'] = true;

        $this->stmt = $this->mysqli->prepare('UPDATE `awt_admin` SET `last_logged_ip` = ? WHERE `id` = ?;');
        $this->stmt->bind_param('ss', $_SERVER['REMOTE_ADDR'], $this->retrieved['id']);
        $this->stmt->execute();
        $this->stmt->close();

        return true;
    }

    public function checkAuthentication()
    {

        $this->sessionHandler();

        if(!isset($_SESSION['userInfo']['token']) && !isset($_SESSION['userInfo']['id'])) {
            return false;
        } else {
            
            $this->stmt = $this->mysqli->prepare('SELECT * FROM `awt_admin` WHERE `id` = ?;');
            $this->stmt->bind_param('s', $_SESSION['userInfo']['id']);
            $this->stmt->execute();
            $this->stmt->store_result();
            if($this->stmt->num_rows != 1) {
                echo "INVALID UID";
                unset($_SESSION['userInfo']);
                exit();
            }

            $this->stmt->bind_result($this->retrieved['id'], $this->retrieved['email'], $this->retrieved['username'], $this->retrieved['firstname'], $this->retrieved['lastname'], $this->retrieved['lastIp'], $this->retrieved['password'], $this->retrieved['token'], $this->retrieved['permission']);
            $this->stmt->fetch();
            
            $this->stmt->close();

            if($_SESSION['userInfo']['token'] != $this->retrieved['token']) {
                echo "INVALID TOKEN";
                unset($_SESSION['userInfo']);
                exit();
            }

            $this->stmt = $this->mysqli->prepare('UPDATE `awt_admin` SET `last_logged_ip` = ? WHERE `id` = ?;');
            $this->stmt->bind_param('si', $_SERVER['REMOTE_ADDR'], $this->retrieved['id']);
            $this->stmt->execute();
            $this->stmt->close();

            return true;

        }
    }

}