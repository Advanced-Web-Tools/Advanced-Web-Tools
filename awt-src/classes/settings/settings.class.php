<?php

namespace settings;

use admin\profiler;
use cache\cache;
use database\databaseConfig;

class settings
{
    private object $database;
    private object $mysqli;
    private object $profiler;
    private object $cache;
    public array $allSettings;

    public function __construct()
    {
        $this->database = new databaseConfig;

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();

        $this->profiler = new profiler;

        $this->cache = new cache;

            $stmt = $this->mysqli->prepare("SELECT * FROM `awt_settings` ORDER BY `id` ASC;");
            $stmt->execute();
            $this->allSettings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();


        
    }

    public function getSettingsValue($name)
    {
        foreach ($this->allSettings as $key => $value) {
            if ($value['name'] == $name) return $value['value'];
        }
        return false;
    }

    public function getSettingsPermission($name)
    {
        foreach ($this->allSettings as $key => $value) {
            if ($value['name'] == $name) return $value['required_permission_level'];
        }
        return false;
    }

    public function setSetting($name, $value, $applied_when = NULL, $required_permission = 0)
    {

        if ($perm = $this->getSettingsValue($name) !== false) {

            if (!$this->profiler->checkPermissions($perm)) return false;

            $stmt = $this->mysqli->prepare("UPDATE `awt_settings` SET `value` = ?, `applied_when` = ?, `required_permission_level` = ? WHERE `name` = ?;");
            $stmt->bind_param('ssss', $value, $applied_when, $required_permission, $name);
            $stmt->execute();
            $stmt->close();

            return true;
        }


        return false;
    }

    public function createSetting($name, $value, $applied_when = NULL)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO `awt_settings` (`name`, `value`, `applied_when`, `required_permission_level`) VALUES (?, ?, ?, ?);");
        $stmt->bind_param('ssss', $name, $value, $applied_when, $this->profiler->permissionLevel);
        $stmt->execute();
        $stmt->close();

        return true;

    }
}
