<?php

namespace TwentyTwentyFive\classes\controller;
use setting\Config;
use Theming\classes\Theme\Menu\ThemeMenu;
use Theming\classes\Theme\Page\ThemePage;
use view\View;

final class TwentyTwentyFiveController extends ThemePage
{
    private Config $config;
    public ?string $name;
    public ?string $contactEmail;
    public ?string $phoneNumber;

    public ?string $address;

    public string $page;
    public ThemeMenu $menu;
    public string $menuHTML;
    public function __construct()
    {
        $this->config = new Config();

        $this->name = $this->config::getConfig("AWT", "Website Name")->getValue();
        $this->contactEmail = $this->config::getConfig("AWT", "Contact Email")->getValue();
        $this->phoneNumber = $this->config::getConfig("AWT", "Phone Number")->getValue();
        $this->address = $this->config::getConfig("AWT", "Address")->getValue();

        $this->menu = new ThemeMenu();
        $this->menuHTML = $this->menu->getHTML();
    }

    public function index(array|string $params): View
    {
        $this->page = $this->matchPageName();

        return $this->view(["name" => $this->name,
            "contact" => $this->contactEmail,
            "phoneNumber" => $this->phoneNumber,
            "address" => $this->address,
            "page" => $this->page,
            "params" => $params,
            "navigation" => $this->menuHTML]);
    }

    public function about(array|string $params): View
    {
        $this->page = $this->matchPageName();
        return $this->view(["name" => $this->name,
            "contact" => $this->contactEmail,
            "phoneNumber" => $this->phoneNumber,
            "address" => $this->address,
            "page" => $this->page,
            "params" => $params,
            "navigation" => $this->menuHTML]);    }

    public function contact(array|string $params): View
    {
        $this->page = $this->matchPageName();
        return $this->view(["name" => $this->name,
            "contact" => $this->contactEmail,
            "phoneNumber" => $this->phoneNumber,
            "address" => $this->address,
            "page" => $this->page,
            "params" => $params,
            "navigation" => $this->menuHTML]);    }
}