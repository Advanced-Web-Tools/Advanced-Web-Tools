<?php

namespace admin;
use session\sessionHandler;

class profiler extends sessionHandler
{
    public string $name;
    public string $email;
    public string $firstname;
    public string $lastname;
    public int $permissionLevel;
    public string $accountType;

    public function __construct()
    {   
        $this->sessionHandler();

        $this->name = 'none';
        $this->email = 'none';
        $this->firstname = 'none';
        $this->lastname = 'none';
        $this->permissionLevel = 4;

        if(isset($_SESSION['userInfo'])) {
            $this->name = $_SESSION['userInfo']['username'];
            $this->email = $_SESSION['userInfo']['email'];
            $this->firstname = $_SESSION['userInfo']['firstname'];
            $this->lastname = $_SESSION['userInfo']['lastname'];
            $this->permissionLevel = $_SESSION['userInfo']['permission'];
            if($this->permissionLevel == 0) $this->accountType = 'Admin';
            if($this->permissionLevel == 1) $this->accountType = 'Moderator';
            if($this->permissionLevel == 2) $this->accountType = 'Author';
        }

    }

    public function getProfile()
    {   
        return array('name' => $this->name, 'email' => $this->email, 'fname' => $this->firstname, 'lname' => $this->lastname, 'type' => $this->accountType);
    }

    public function checkPermissions($requiredPerm)
    {
        if($this->permissionLevel == 0) return true;
        if($this->permissionLevel > $requiredPerm) return false;
        return true;
    }
}

