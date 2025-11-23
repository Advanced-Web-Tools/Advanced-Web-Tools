<?php

namespace Theming\classes\ThemeAPI;

use event\EventDispatcher;
use object\ObjectFactory;
use router\Router;
use Theming\classes\Theme\Page\ThemePage;
use Theming\classes\Theme\Settings\ThemeSettings;

abstract class ThemeAPI
{
    public string $name = "";
    public string $description = "";
    public array $routes = [];
    public array $controllers = [];
    public EventDispatcher $eventDispatcher;
    public array $pages = [];
    public int $themeID = 0;
    public ThemeSettings $themeSettings;
    abstract public function buildTheme(): void;

    public function addPage(string $name, string $route, string $viewName): void
    {
        $page = [
            "name" => $name,
            "route" => $route,
            "viewName" => $viewName
        ];

        $this->pages[] = $page;
    }

    public function addSettings(ThemeSettings $settings): void
    {
        $this->themeSettings = $settings;
    }

    public function getPages(): array
    {
        return $this->pages;
    }

    protected function setName(string $name): void
    {
        $this->name = $name;
    }

    protected function setDescription(string $description): void
    {
        $this->description = $description;
    }

    protected function addRouter(Router $router): void
    {
        $this->routes[] = $router;
    }

    protected function addController(ObjectFactory|ThemePage $controller): void
    {
        if($controller instanceof ObjectFactory) {
            $controller->addMethodCall("setID")
                ->addMethodCall("addPages")
                ->addMethodCall("setSettings")
                ->addMethodArgs("setID", [$this->themeID])
                ->addMethodArgs("addPages", [$this->pages])
                ->addMethodArgs("setSettings", [$this->themeSettings]);
            return;
        }

        $controller->setID($this->themeID)->addPages($this->pages)->setSettings($this->themeSettings);
        $this->controllers[] = $controller;
    }

}