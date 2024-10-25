<?php


use packages\runtime\api\RuntimeAPI;
use packages\runtime\handler\enums\ERuntimeFlags;

class TwentyTwentyFour extends RuntimeAPI
{
    public function environmentSetup(): void
    {
        $this->setRuntimeFlag(ERuntimeFlags::WaitForPackage);
        $this->waitForRuntime("ThemeFY!");
    }

    public function setup(): void
    {
        // TODO: Implement setup() method.
    }

    public function main(): void
    {

    }
}