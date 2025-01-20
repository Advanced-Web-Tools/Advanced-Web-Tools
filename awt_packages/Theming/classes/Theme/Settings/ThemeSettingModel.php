<?php

namespace Theming\classes\Theme\Settings;

use model\Model;

final class ThemeSettingModel extends Model
{
    public int $id;
    public function __construct(int $id)
    {
        parent::__construct();
        $this->id = $id;

        $this->selectByID($this->id, "theming_settings");
    }
}