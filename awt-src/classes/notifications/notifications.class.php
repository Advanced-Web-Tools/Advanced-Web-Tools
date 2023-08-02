<?php

namespace notifications;
use database\databaseConfig;

class notifications {
    private object $mysqli;
    private object $database;
    public string $caller;
    public string $content;
    public string $importance; 
    public function __construct(string $caller = "", string $content = "", string $importance = "low") 
    {

        $this->caller = $caller;
        $this->content = $content;
        $this->importance = $importance;

        $this->database = new databaseConfig();

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();

    }

    public function createNotification(string $caller, string $content, string $importance)
    {
        $this->caller = $caller;
        $this->content = $content;
        $this->importance = $importance;
    }

    public function pushNotification()
    {
        $stmt = $this->mysqli->prepare("INSERT INTO `awt_notifications`(`caller`, `content`, `importance`) VALUES (?, ?, ?) ;");
        $stmt->bind_param("sss", $this->caller, $this->content, $this->importance);
        $stmt->execute();
    }

    public function getNotifications(int $limit)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_notifications` ORDER BY `time` DESC LIMIT ?;");
        $stmt->bind_param("s", $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }


}