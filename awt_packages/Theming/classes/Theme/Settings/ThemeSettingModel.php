<?php

namespace Theming\classes\Theme\Settings;

use model\Model;

final class ThemeSettingModel extends Model
{
    public int $id;
    public int $theme_id;
    public string $value;
    public string $type;
    public string $name;

    public function __construct(array $data)
    {
        parent::__construct();
        $this->id = $data["id"];
        $this->model_id = $data["id"];
        $this->model_source = "theming_settings";
        $this->value = $data["value"];
        $this->type = $data["type"];
        $this->id_column = "id";
        $this->name = $data["name"];
        $this->theme_id = $data["theme_id"];
    }

    public function changeValue(string $value): void
    {
        $this->value = $value;
    }
}