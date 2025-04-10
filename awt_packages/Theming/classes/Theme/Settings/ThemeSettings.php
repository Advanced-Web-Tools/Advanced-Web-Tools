<?php

namespace Theming\classes\Theme\Settings;

use database\DatabaseManager;

class ThemeSettings
{
    private int $id;

    private DatabaseManager $database;
    public array $settings;
    public function __construct(int $id)
    {
        $this->database = new DatabaseManager();
        $this->id = $id;

        $result = $this->database->table("theming_settings")
        ->select(["*"])
        ->where(["theme_id" => $this->id])->orderBy("type")->get();


        foreach ($result as $setting) {
            $this->settings[$setting["name"]] = new ThemeSettingModel($setting["id"]);
        }
    }
}