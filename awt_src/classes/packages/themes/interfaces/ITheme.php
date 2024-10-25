<?php

namespace packages\themes\interfaces;

use router\Router;

interface ITheme
{
    public function setThemeFiles(string $path, string $type): array;
    public function addRouter(Router $router): void;
}