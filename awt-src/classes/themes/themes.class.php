<?php

namespace themes;

use database\databaseConfig;
use admin\profiler;

class themes extends modules
{
    private object $dataBase;
    private $authorized;
    private object $mysqli;
    protected array $themes;
    public array $activeTheme;
    public string $themeName;
    public string $themeVersion;
    public string $themeAuthor;
    public string $linkToThemeDir;

    public array $settingsPage;

    public function __construct()
    {
        $this->dataBase = new databaseConfig;
        $this->authorized = $this->dataBase->checkAuthority();

        $this->authorized = $this->dataBase->checkAuthority() == 1 or die("Fatal error database access for " . $this->dataBase->getCaller() . " was denied");
        $this->mysqli = $this->dataBase->getConfig();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_themes` ORDER BY `id` ASC;");
        $stmt->execute();
        $this->themes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    public function getThemes()
    {
        return $this->themes;
    }

    public function getActiveTheme()
    {
        foreach ($this->themes as $key => $value) {
            if ($value['active'] == 1) {
                $this->activeTheme = $this->themes[$key];
                return $this->activeTheme;
            }
        }
    }

    public function loadTheme()
    {
        global $theme;
        global $dependencies;
        global $engines;
        global $settings;
        global $navbar;
        global $widgets;
        global $aio;
        global $menu;
        global $pluginPages;
        global $pages;
        global $dashboardWidgets;
        global $floatingEditor;

        $this->getActiveTheme();

        file_exists(THEMES . $this->activeTheme['name'] . DIRECTORY_SEPARATOR . "theme.php") or die("Error loading theme.");

        $this->linkToThemeDir = HOSTNAME . "awt-content/themes/" . $this->activeTheme['name'];

        include_once THEMES . $this->activeTheme['name'] . DIRECTORY_SEPARATOR . "theme.php";
    }

    public function loadCSS($path)
    {
        echo '<link rel="stylesheet" href="' . $this->linkToThemeDir . $path . '">';
    }

    public function getAssetLink($path)
    {
        return $this->linkToThemeDir . $path;
    }

    public function retrieveCSSColors($xmlFilePath)
    {
        $colors = array();

        if (file_exists($xmlFilePath)) {
            $xml = simplexml_load_file($xmlFilePath);

            foreach ($xml->csscolors->children() as $child) {
                $tagName = $child->getName();
                $tagValue = (string)$child;
                $colors[$tagName] = $tagValue;
            }
        }

        return $colors;
    }

    public function addSettingsPage($name, $path, $global = false)
    {
        if (!$global) $this->settingsPage[$name]['path'] = $path;
        if ($global) $this->settingsPage['global']['path'] = $path;
        return $this->settingsPage;
    }

    public function loadSettingsPage($name)
    {
        if (array_key_exists($name, $this->settingsPage)) return $this->settingsPage[$name]['path'];
        return false;
    }

    public function loadGlobalSettingsPage()
    {
        if (array_key_exists("global", $this->settingsPage)) return $this->settingsPage["global"]['path'];
        return false;
    }

    public function loadThemePage(string $name)
    {   
        global $theme;
        global $paging;
        $this->linkToThemeDir = HOSTNAME . "awt-content/themes/" . $this->activeTheme['name'];
        include_once THEMES . $this->activeTheme['name'] . DIRECTORY_SEPARATOR . "pages".DIRECTORY_SEPARATOR.$name.".php";
    }

    public function enableTheme(int $id, profiler $profiler) {

        if($profiler->checkPermissions(0)) {
            
            $status = 1;

            $oldThemeStatus = 0;

            $stmt = $this->mysqli->prepare("UPDATE `awt_themes` SET `active` = ? WHERE `active` = ?;");
            $stmt->bind_param("ss", $oldThemeStatus, $status);
            $stmt->execute();

            $stmt = $this->mysqli->prepare("UPDATE `awt_themes` SET `active` = ? WHERE `id` = ?;");
            $stmt->bind_param("ss", $status, $id);
            $stmt->execute();

            return true;

        } else {
            return false;
        }

    }
}
