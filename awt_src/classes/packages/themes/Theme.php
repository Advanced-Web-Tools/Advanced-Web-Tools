<?php

namespace packages\themes;

use packages\Package;
use packages\themes\manager\enums\EThemeStatus;

class Theme extends Package
{
    public int $themeId;
    public EThemeStatus $status = EThemeStatus::Disabled;
    public string $installedByUsername;
    public function __construct() {
        parent::__construct();
        $this->packagePath = THEMES . $this->name . DIRECTORY_SEPARATOR;
    }

    public function getInfoArray(): array {
        return [
            "name" => $this->name,
            "description" => $this->description,
            "author" => $this->author,
            "version" => $this->version,
            "minimum awt version" => $this->minimumAwtVersion,
            "maximum awt version" => $this->maximumAwtVersion,
        ];
    }
}