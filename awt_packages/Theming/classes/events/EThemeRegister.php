<?php

namespace Theming\classes\events;

use event\interfaces\IEvent;
use Theming\classes\ThemeAPI\ThemeAPI;

final class EThemeRegister implements IEvent
{

    private ThemeAPI $themeAPI;
    private int $packageID;

    public function addTheme(ThemeAPI $themeAPI, int $packageID): void
    {
        $this->themeAPI = $themeAPI;
        $this->packageID = $packageID;
    }

    public function getName(): string
    {
        return 'theme.register';
    }

    public function bundle(): array
    {
        return ["theme" => $this->themeAPI, "packageID" => $this->packageID];
    }
}