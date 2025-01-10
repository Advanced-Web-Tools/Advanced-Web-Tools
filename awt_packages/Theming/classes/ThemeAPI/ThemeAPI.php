<?php

namespace Theming\classes\ThemeAPI;

use event\EventDispatcher;
use router\Router;
use Theming\classes\Theme\ThemePage;

abstract class ThemeAPI
{
    public string $name = "";
    public string $description = "";
    public array $routes = [];
    public array $controllers = [];
    public EventDispatcher $eventDispatcher;
    public array $pages = [];
    public int $themeID = 0;

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

    protected function addController(ThemePage $controller): void
    {
        $controller->setID($this->themeID)->addPages($this->pages);
        $this->controllers[] = $controller;
    }

}