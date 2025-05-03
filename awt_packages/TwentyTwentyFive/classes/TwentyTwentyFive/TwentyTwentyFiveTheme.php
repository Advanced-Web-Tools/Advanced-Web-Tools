<?php

namespace TwentyTwentyFive\classes\TwentyTwentyFive;

use controller\Controller;
use router\Router;
use Theming\classes\ThemeAPI\ThemeAPI;
use TwentyTwentyFive\classes\controller\TwentyTwentyFiveController;

final class TwentyTwentyFiveTheme extends ThemeAPI
{
    private Controller $controller;
    public function buildTheme(): void
    {
        $this->setName("TwentyTwentyFive");
        $this->setDescription("Advanced Web Tools - Default theme!");


        $this->addPage("Home", "/", "index");
        $this->addPage("About", "/about", "about");
        $this->addPage("Contact", "/contact", "contact");

        $this->buildController();
        $this->addRouter(new Router("/", "index", $this->controller));
        $this->addRouter(new Router("/about", "about", $this->controller));
        $this->addRouter(new Router("/contact", "contact", $this->controller));
        $this->addRouter(new Router("/TwentyTwentyFive/MainCSS", "MainCSS", $this->controller));
    }

    private function buildController(): void
    {
        $this->controller = new TwentyTwentyFiveController();
        $this->controller->setName("TwentyTwentyFive");
        $this->controller->setRootPath(PACKAGES . "/TwentyTwentyFive");
        $this->controller->setViewPath("/views/");
        $this->controller->packageName = "TwentyTwentyFive";
        $this->controller->localAssetPath = "/awt_packages/TwentyTwentyFive/views/assets";
        $this->addController($this->controller);
    }

}