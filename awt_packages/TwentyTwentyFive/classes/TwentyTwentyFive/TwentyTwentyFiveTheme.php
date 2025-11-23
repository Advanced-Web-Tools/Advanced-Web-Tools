<?php

namespace TwentyTwentyFive\classes\TwentyTwentyFive;

use controller\Controller;
use object\ObjectFactory;
use router\Router;
use Theming\classes\ThemeAPI\ThemeAPI;
use TwentyTwentyFive\classes\controller\TwentyTwentyFiveController;

final class TwentyTwentyFiveTheme extends ThemeAPI
{
    private ObjectFactory $controller;
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
        try {
            $objectFactory = new ObjectFactory();
            $props = [
                "packageName" => "TwentyTwentyFive",
                "localAssetPath" => "/awt_packages/TwentyTwentyFive/views/assets"
            ];
            $methodCalls = [
                "setName",
                "setRootPath",
                "setViewPath",
            ];
            $methodArgs = [
                "setName" => ["TwentyTwentyFive"],
                "setRootPath" => [PACKAGES . "/TwentyTwentyFive"],
                "setViewPath" => ["/views/"],
            ];

            $objectFactory->setClassName(TwentyTwentyFiveController::class);
            $objectFactory->setProperties($props);
            $objectFactory->setMethodCalls($methodCalls);
            $objectFactory->setMethodArgs($methodArgs);
            $objectFactory->setType(TwentyTwentyFiveController::class);

            $this->controller = $objectFactory;
            $this->addController($this->controller, "TwentyTwentyFive");

        } catch (Exception $e) {
            if (DEBUG) {
                die($e->getMessage());
            }
        }
    }

}