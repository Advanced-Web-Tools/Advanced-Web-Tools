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

        if ($this->auth = $this->database->checkAuthority() == 1) {

            $this->mysqli = $this->database->getConfig();

            if (!$this->database->checkForTable("awt_metrics")) {

                $query = "CREATE TABLE `awt_metrics` (
                    `id` int(255) NOT NULL,
                    `uid` varchar(255) NOT NULL,
                    `url` varchar(255) NOT NULL,
                    `ip` varchar(255) NOT NULL,
                    `date` date NOT NULL DEFAULT current_timestamp()
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

                $stmt = $this->mysqli->prepare($query);

                $stmt->execute();
            }
            
        }

        $this->uid = hash('SHA512', time());
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->date = date("Y-m-d");
    }

    public function startMetrics()
    {
        if ($this->auth == 0) return false;

        if (!isset($_COOKIE['METRICS'])) {
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

    public function getMetricsUnique()
    {

        if ($this->auth == 0) return false;

        $stmt = $this->mysqli->prepare("SELECT DISTINCT `uid` FROM `awt_metrics`;");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->num_rows;
    }

    public function getMetricsTotal()
    {

        if ($this->auth == 0) return false;

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_metrics`;");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->num_rows;
    }

    public function getMostVisited()
    {

        if ($this->auth == 0) return false;

        $stmt = $this->mysqli->prepare("SELECT `url`, COUNT(`url`) AS `value_occurrence` FROM `awt_metrics` GROUP BY `url` ORDER BY `value_occurrence` DESC LIMIT 1;");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);;
        $stmt->close();
        return $result;
    }
}
