<?php

use notifications\notifications;
use admin\authentication;


class awtWidgets  {

    private object $notifications;
    private object $auth;
    private bool $authenticated;

    public function __construct()
    {
        $this->notifications = new notifications;
        $this->auth = new authentication;
        $this->authenticated = $this->auth->checkAuthentication();
    }


    public function notificationWidget(int $limit)
    {
        return $this->notificationFormatter($this->notifications->getNotifications($limit));
    }

    private function notificationFormatter(array $notifications) {
        $return = "";
        foreach($notifications as $key => $value) {
            $return .= "<div class='notification ".$value['importance']."'>";
            $return .= "<h4>".$value['caller']."</h4>";
            $return .= "<p>".$value['content']."</p></div>";
        }
        return $return;
    }

    public function pluginStatsWidget() {
        global $loadedPlugins;
        return count($loadedPlugins);
    }

}