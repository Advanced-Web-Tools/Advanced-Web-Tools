<?php

namespace api;

use notifications\notifications;
use settings\settings;

abstract class api
{

    private settings $settings;

    private notifications $notifications;

    private string $whitelist;

    private string $origin;

    private string $apiEnabled;

    /*
    This will be overriden in child class
    Use this to fix it
    public function __construct()
    {
        parent::__construct();

        Code...

    }

    */

    public function __construct()
    {
        $this->settings = new settings();
        $this->apiEnabled = $this->settings->getSettingsValue("Enable API");
        $this->whitelist = $this->settings->getSettingsValue("API request whitelist");

        if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $this->origin = $_SERVER['HTTP_ORIGIN'];
        }
        else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $this->origin = $_SERVER['HTTP_REFERER'];
        } else {
            $this->origin = $_SERVER['REMOTE_ADDR'];
        }

        $this->whitelist .= " http://localhost https://localhost ::1 127.0.0.1";

        $this->notifications = new notifications;
    }


    /*
    This should be overriden in child class

    public function Api()
    {
        parent::Api();

        Code...

    }

    */

    public function Api()
    {
        $this->checkIfRequestInWhitelist() == "true" or die("$this->origin is not allowed here!");
    }


    public function checkForData() : bool
    {
        if(isset($_POST['data'])) return true;
        return false;
    }

    public function checkIfRequestInWhitelist(): bool
    {

        if ($this->apiEnabled != "true") return false;

        if (str_starts_with($this->whitelist, "*"))
        {
            $this->createHeaders();

            return true;
        }

        if (str_contains($this->whitelist, $this->origin)) {
            $this->createHeaders();
            return true;
        }

        $this->notifications->createNotification("API", "API Request Violation: $this->origin is not in the whitelist!", "Incident");
        $this->notifications->pushNotification();

        return false;

    }

    private function createHeaders() : void
    {
        header("Access-Control-Allow-Origin: $this->origin");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        }
    }

}