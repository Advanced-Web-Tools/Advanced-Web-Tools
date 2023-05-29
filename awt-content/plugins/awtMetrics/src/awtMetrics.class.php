<?php

use database\databaseConfig;

class awtMetrics 
{
    private object $mysqli;
    private object $database;
    private $auth;
    protected $uid;
    protected $ip;
    protected $url;
    public $date;
    public array $metrics;

    public function __construct() 
    {
        $this->database = new databaseConfig;
        
        if($this->auth = $this->database->checkAuthority() == 1) {
            $this->mysqli = $this->database->getConfig();
        }

        $this->uid = hash('SHA512', time());
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->date = date("Y-m-d");
    }

    public function startMetrics()
    {
        if($this->auth == 0) return false;

        if(!isset($_COOKIE['METRICS'])) {
            setcookie('METRICS', $this->uid, time() + (24 * 3600), '/');
        } else {
            $this->uid = $_COOKIE['METRICS'];
        }

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_metrics` (`uid`, `url`, `ip`, `date`) VALUES (?, ?, ?, ?);");
        $stmt->bind_param('ssss', $this->uid, $this->url, $this->ip, $this->date);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}