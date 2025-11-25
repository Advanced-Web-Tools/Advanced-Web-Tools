<?php

namespace setting;

use database\DatabaseManager;
use setting\model\SettingModel;

class Settings
{
    private DatabaseManager $database;
    private array $settings;
    public function __construct()
    {
        $this->database = new DatabaseManager();
        $this->settings = [];
    }

    public function fetchSettings(): self
    {
        $result = $this->database->
        table("awt_setting")->
        select(["awt_setting.id", "awt_package.name AS package_name", "awt_setting.package_id", "awt_setting.name", "awt_setting.value", "awt_setting.value_type", "awt_setting.category", "awt_setting.required_permission_level"])->
        join("awt_package", "awt_package.id = awt_setting.package_id")->
        get();

        foreach ($result as $setting) {
            $this->settings[$setting["id"]] = new SettingModel($setting["id"], $setting["package_name"], $setting);
        }

        return $this;
    }

    public function initSettings(): self
    {
        foreach ($this->settings as $setting) {
            $setting->createObjectConstant();
        }
        return $this;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getSetting(string $name): ?SettingModel
    {
        foreach ($this->settings as $setting) {
            if ($setting->getName() === $name) {
                return $setting;
            }
        }

        return null;
    }

    public function createSetting(int $package_id, string $name, string $value, int $perm, string $value_type = "text", string $category = "Miscellaneous"): int
    {


        $res = $this->database->table("awt_setting")->select(["id"])->where(["package_id" => $package_id, "name" => $name])->get();

        if($res !== null && count($res) > 0)
            return 0;

        return $this->database->table("awt_setting")->insert([
            "package_id" => $package_id,
            "name" => $name,
            "value" => $value,
            "value_type" => $value_type,
            "category" => $category,
            "required_permission_level" => $perm
        ])->executeInsert();
    }

}
