<?php

use packages\runtime\api\RuntimeAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use Theming\classes\events\EThemeRegister;
use TwentyTwentyFive\classes\TwentyTwentyFive\TwentyTwentyFiveTheme;

final class TwentyTwentyFive extends RuntimeAPI
{

    public function environmentSetup(): void
    {
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::WaitForPackage);
        $this->waitForRuntime("Theming");
    }

    public function setup(): void
    {

    }


    public function main(): void
    {
        $theme = new TwentyTwentyFiveTheme();
        $event = new EThemeRegister();

        $event->addTheme($theme, $this->id);

        $this->eventDispatcher->dispatch($event);
    }
}