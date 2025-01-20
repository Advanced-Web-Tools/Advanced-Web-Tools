<?php

namespace TwentyTwentyFive\classes\controller;
use redirect\Redirect;
use setting\Config;
use Theming\classes\Theme\Menu\ThemeMenu;
use Theming\classes\Theme\Page\ThemePage;
use view\View;

final class TwentyTwentyFiveController extends ThemePage
{
    private Config $config;
    public ?string $name;
    public string $page;
    public ThemeMenu $menu;
    public string $menuHTML;
    public function __construct()
    {
        $this->config = new Config();

        $this->name = $this->config::getConfig("AWT", "Website Name")->getValue();

        $this->menu = new ThemeMenu();
        $this->menuHTML = $this->menu->getHTML();
    }

    public function index(array|string $params): View|Redirect
    {
        $this->page = $this->matchPageName();

        return $this->view(["name" => $this->name, "page" => $this->page, "params" => $params]);
    }
}