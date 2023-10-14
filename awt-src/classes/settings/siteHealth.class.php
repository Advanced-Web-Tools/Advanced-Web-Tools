<?php

namespace settings;
use notifications\notifications;

class siteHealth extends notifications {
    public int $numberOfIncidents;
    public int $numberOfIncidentsToday;
    public int $numberOfNotices;
    public int $numberOfNoticesToday;

    public array $incidentsToday;
    public array $incidents;

    public array $noticesToday;
    public array $notices;

    public string $health;

    public function __construct()
    {   
        $this->lateConstruct();
        $this->incidentsToday = $this->getNotificationsByDate(date("Y-m-d"), "incident");
        $this->incidents = $this->getNotificationsOfType(100, "incident");

        $this->noticesToday = $this->getNotificationsByDate(date("Y-m-d"), "notice");
        $this->notices = $this->getNotificationsOfType(100, "notice");

        $this->numberOfIncidentsToday = count($this->incidentsToday);
        $this->numberOfIncidents = count($this->incidents);

        $this->numberOfNoticesToday = count($this->noticesToday);
        $this->numberOfNotices = count($this->notices);
    }

    public function getHealth() : string
    {   

        if($this->numberOfIncidentsToday == 0) {
            $this->health = "Excellent";
            return $this->health;
        }

        if($this->numberOfIncidentsToday > 0 && $this->numberOfIncidentsToday < 5) {
            $this->health = "Good";
            return $this->health;
        }

        if($this->numberOfIncidentsToday >= 5 && $this->numberOfIncidentsToday < 10) {
            $this->health = "Medium";
            return $this->health;
        }

        if($this->numberOfIncidentsToday >= 10 && $this->numberOfIncidentsToday < 15) {
            $this->health = "Bad";
            return $this->health;
        }

        if($this->numberOfIncidentsToday >= 15) {
            $this->health = "Critical";
            return $this->health;
        }

        return "Could not determine";
    }

}