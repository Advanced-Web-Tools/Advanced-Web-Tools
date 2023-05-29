<?php

namespace admin;

use database\databaseConfig;
use session\sessionHandler;

class admin extends sessionHandler
{

    public function signOut()
    {
        $this->sessionHandler();
        $this->sessionClearing();
    }
    
    

}