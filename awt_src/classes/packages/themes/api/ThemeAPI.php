<?php

namespace packages\themes\api;

use packages\themes\interfaces\ITheme;
use packages\themes\Theme;
use router\Router;

abstract class ThemeAPI extends Theme implements ITheme
{
    public array $routers;
    public array $files;

    final function __construct() {
        parent::__construct();
    }

    final function addRouter(Router $router): void
    {
        $this->routers[$router->name] = $router;
    }
}