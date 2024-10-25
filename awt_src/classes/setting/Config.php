<?php

namespace setting;

use setting\model\SettingModel;

final class Config
{
    static private SettingModel $settingModel;

    public static function getConfig($package_name, $setting_name): ?SettingModel
    {
        $name = "SETT_" .
            strtoupper(
                str_replace('-', '_', str_replace(' ', '_', $package_name))
                . "_" .
                str_replace('-', '_', str_replace(' ', '_', $setting_name))
            );

        if (defined($name)) {
            static::$settingModel = constant($name);
        } else {
            return null;
        }
        return static::$settingModel;
    }
}