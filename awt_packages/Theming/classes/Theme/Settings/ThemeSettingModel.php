<?php

namespace Theming\classes\Theme\Settings;

use model\Model;

final class ThemeSettingModel extends Model
{
    public int $id;
    public string $value;

    public function __construct(int $id)
    {
        parent::__construct();
        $this->id = $id;

        $this->selectByID($this->id, "theming_settings");
    }

    public function changeValue(string $value): void
    {
        $this->value = $value;
    }
}