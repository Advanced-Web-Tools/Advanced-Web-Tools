<?php

namespace Theming\classes\events;
use event\EventDispatcher;
use event\interfaces\IEvent;
use event\interfaces\IEventListener;
use router\events\EDynamicRoute;
use Theming\classes\ThemeAPI\ThemeAPI;

final class EThemeRegisterListener implements IEventListener
{

    private int $activeTheme;

    private int $themePackageID = 0;
    private array $themeAPI = [];


    public EventDispatcher $eventDispatcher;
    public function setActiveTheme(int $package, int $activeTheme): void
    {
        $this->themePackageID = $package;
        $this->activeTheme = $activeTheme;
    }

    public function registerTheme(int $packageID, ThemeAPI $themeAPI): void
    {
        $this->themePackageID = $packageID;
        $this->themeAPI[$this->themePackageID] = $themeAPI;

        $themeAPI->buildTheme();
        $dynamicRoute = new EDynamicRoute();

        foreach ($themeAPI->routes as $route) {
            $dynamicRoute->addRoute($route);
        }

        $this->eventDispatcher->dispatch($dynamicRoute);
    }

    public function handle(IEvent $event): array
    {
        if($event instanceof EThemeRegister) {
            $theme = $event->bundle();

            if($this->themePackageID === $theme["packageID"])
                $theme["theme"]->themeID = $this->activeTheme;
                $this->registerTheme($theme["packageID"], $theme["theme"]);

                $pages = $theme["theme"]->getPages();

                $pagesEvent = new EThemePagesListener();
                $pagesEvent->pages = $pages;

                $this->eventDispatcher->addListener("theming.pages.get", $pagesEvent);
        }

        return $event->bundle();
    }
}