<?php

use notifications\notifications;
use admin\authentication;
use paging\paging;
use themes\themes;


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

    public function incidentsWidget()
    {
        return count($this->notifications->getNotificationsByDate(date('Y-m-d'), "incident"));
    }

    public function noticesWidget()
    {
        return count($this->notifications->getNotificationsByDate(date('Y-m-d'), "notice"));
    }

    public function themesWidget() {
        $theme = new themes;
        return $theme->getActiveTheme();
    }

    public function pagesWidget() {
        $pages = new paging(array());

        $pages = $pages->getAllPages();

        
        foreach($pages as $key => $value) {
            $pageList[] = $value["name"];
        }

        return $pageList;

    }

}