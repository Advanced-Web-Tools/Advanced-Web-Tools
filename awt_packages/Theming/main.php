<?php

use database\DatabaseManager;
use packages\runtime\api\RuntimeLinkerAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use Theming\classes\events\EThemeRegisterListener;
use Theming\event\MenuDrawListener;

final class Theming extends RuntimeLinkerAPI {

    private MenuDrawListener $event;
    private DatabaseManager $database;
    public ?int $activeThemeID;
    public ?int $themePackageID;

    private array $themes;

    public function environmentSetup(): void
    {
        parent::environmentSetup();

        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);

        $this->setRuntimeFlag(ERuntimeFlags::WaitForPackage);

        $this->waitForRuntime("Quil");

    }

    public function setup(): void
    {
        $this->event = new MenuDrawListener();

        $this->eventDispatcher->addListener("menu.request", $this->event);

        $this->database = new DatabaseManager();

        $result = $this->database->table("theming_theme")->select(["*"])->where(["status" => 1])->get()[0];

        $this->activeThemeID = $result["id"];
        $this->themePackageID = $result["package_id"];

        if($this->themePackageID === null)
            $this->themePackageID = 0;

        $themeEvent = new EThemeRegisterListener();
        $themeEvent->setActiveTheme($this->themePackageID, $this->activeThemeID);
        $themeEvent->eventDispatcher = $this->eventDispatcher;
        $this->eventDispatcher->addListener("theme.register", $themeEvent);

    }

    public function main(): void
    {
        $this->createLink("ThemingControllerAPI", "/controllers/ThemingControllerAPI.php");
        $this->createLink("ThemingRouterAPI", "/routes/ThemingRouterAPI.php");
    }
}