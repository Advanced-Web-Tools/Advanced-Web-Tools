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
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_notifications` WHERE `importance` != ? AND `importance` != ? ORDER BY `time` DESC LIMIT ?;");
        $incident = "incident";
        $notice = "notice";
        $stmt->bind_param("sss", $incident, $notice, $limit);        
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function getNotificationsOfType(int $limit, string $type) {
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_notifications` WHERE `importance` = ? ORDER BY `time` DESC LIMIT ?;");
        $stmt->bind_param("ss", $type, $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function getNotificationsByDate(string $date, string $type) {
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_notifications` WHERE `time` BETWEEN ? AND ? AND `importance` = ? ORDER BY `time` DESC;");
        $date_start = $date." 00:00:00";
        $date_end = $date." 23:59:59";
        $stmt->bind_param("sss", $date_start, $date_end, $type);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }


}