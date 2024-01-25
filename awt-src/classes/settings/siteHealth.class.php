<?php

namespace settings;
use notifications\{notifications, attention};

class siteHealth extends notifications {
    public int $numberOfIncidents;
    public int $numberOfIncidentsToday;
    public int $numberOfNotices;
    public int $numberOfNoticesToday;
    public int $numberOfUnresolvedAttentions;
    public array $incidentsToday;
    public array $incidents;
    public array $noticesToday;
    public array $notices;
    public string $health;
    private int $maxHealth = 100;

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

        $attentions = new attention("Health", "check");

        $this->numberOfUnresolvedAttentions = count($attentions->getUnresolved());

    }

    private function calculatehealth() : void
    {
        $this->maxHealth -= $this->numberOfUnresolvedAttentions * 2;
        $this->maxHealth -= $this->numberOfIncidentsToday * 3;
    }

    public function getHealth() : string
    {   

        $this->calculatehealth();

        if($this->maxHealth <= 100 && $this->maxHealth > 95) {
            $this->health = "Excellent";
            return $this->health;
        }

        if($this->maxHealth <= 95 && $this->maxHealth > 85) {
            $this->health = "Good";
            return $this->health;
        }

        if($this->maxHealth <= 85 && $this->maxHealth > 75) {
            $this->health = "Medium";
            return $this->health;
        }

        if($this->maxHealth <= 75 && $this->maxHealth > 65) {
            $this->health = "Bad";
            return $this->health;
        }

        if($this->maxHealth <= 65) {
            $this->health = "Critical";
            return $this->health;
        }

        return "Could not determine";
    }

}