<?php

namespace paging;
use database\databaseConfig;
use admin\profiler;
use notifications\notifications;

class editor extends paging
{
    public function __construct(array $pluginPages)
    {
        parent::__construct($pluginPages);

        $this->database = new databaseConfig;

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();
    }


    public function updatePage(string $name, string $page, string $status = "live", int $override = 0)
    {
        $page_length = strlen($page);

        $renderer = new renderer($this->pluginPages);

        $page = $renderer::sanitizePage($page);

        if ($page_length > 16777215) {
            $content_1 = substr($page, 0, 16777214);
            $content_2 = substr($page, 16777214, 16777214 * 2);
        } else {
            $content_1 = $page;
            $content_2 = "";
        }

        $stmt = $this->mysqli->prepare("UPDATE `awt_paging` SET `content_1` = ?, `content_2` = ?, `status` = ?, `override` = ? WHERE `name` = ?");
        $stmt->bind_param("sssis", $content_1, $content_2, $status, $override, $name);
        $stmt->execute();
        $stmt->close();

        $profiler = new profiler();

        $notifications = new notifications("Pages", $profiler->name . " has updated content of $name page.");
        $notifications->pushNotification();
    }

    public function uploadPage(string $name, string $page, string $status = "live", int $override = 0)
    {


        if($this->searchPage("name", $name)) {
            $this->updatePage($name, $page, $status, $override);
            return true;
        }

        $page_lenght = strlen($page);

        if ($page_lenght > 16777215) {
            $content_1 = substr($page, 0, 16777214);
            $content_2 = substr($page, 16777214, 16777214 * 2);
        } else {
            $content_1 = $page;
            $content_2 = "";
        }

        $token = hash("sha512", time());

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_paging`(`id`, `name`, `content_1`, `content_2`, `status`, `token`, `override`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssi", $name, $content_1, $content_2, $status, $token, $override);
        $stmt->execute();
        $stmt->close();

        $profiler = new profiler();

        $notifications = new notifications("Pages", $profiler->name . " has uploaded content to $name page.");
        $notifications->pushNotification();
    }


    public function createEmptyPage(string $name) : bool
    {
        $content_1 = "<div class='pageSection'>";
        $content_2 = "</div>";
        $status = "preview";
        $override = 0;

        $token = hash("sha512", time());

        if($this->searchPage("name", $name)) return false;

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_paging`(`id`, `name`, `content_1`, `content_2`, `status`, `token`, `override`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssi", $name, $content_1, $content_2, $status, $token, $override);
        $stmt->execute();
        $stmt->close();

        $profiler = new profiler();

        $notifications = new notifications("Pages", $profiler->name . " has created new page: $name.");
        $notifications->pushNotification();

        return true;
    }

    public function loadPageEdit(int $id)
    {   

        global $theme;
        global $render;

        $result = array();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_paging` WHERE `id` = ?;");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['id'], $result['description'], $result['name'], $result['content_1'], $result['content_2'], $result['status'], $result['token'], $result['override']);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {
            echo $result['content_1'] . $result['content_2'];
            $stmt->close();
            return 1;
        } else {
            die("Page does not exist");
        }
    }

    public function getEveryPage() : array
    {   
        global $builtInPages;
        $result = $this->getAllPages();
        $result = array_merge($builtInPages, $result);
        return $result;
    }

    public function changeInfo(int $id, string $change, string $value) : bool
    {
        $stmt = $this->mysqli->prepare("UPDATE `awt_paging` SET `" . $change . "` = ? WHERE `id` = ?");

        $stmt->bind_param("si", $value, $id);

        if($stmt->execute()) return true;

        return false;
    }

    public function deletePage(int $id) : bool
    {
        $stmt = $this->mysqli->prepare("DELETE FROM `awt_paging` WHERE `id` = ?;");
        $stmt->bind_param("i", $id);
        
        if(!$stmt->execute()) {
            $stmt->close();
            return false;
        }

        $stmt->close();

        $profiler = new profiler();

        $notifications = new notifications("Pages", $profiler->name . " has deleted page with ID: $id.", "high");
        $notifications->pushNotification();

        return true;
    }

}