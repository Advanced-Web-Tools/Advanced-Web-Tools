<?php

namespace render\TemplateEngine\Data;

final class DataFunction {
    private static $package_name;

    public function __construct($name) {
        self::$package_name = $name;
    }

    public function data(?array $args) {
        $pn = self::$package_name;
        return HOSTNAME . "awt_data/media/$args[1]/$pn/$args[0]";
    }
}