<?php
use themes\settings;

define("THEME_DIR", __DIR__ . DIRECTORY_SEPARATOR);
define("THEME_PAGES_DIR", THEME_DIR . "pages" . DIRECTORY_SEPARATOR);
define("THEME_MODULES_DIR", THEME_DIR . "modules" . DIRECTORY_SEPARATOR);
define("THEME_NAME", "Twenty-Twenty-Three");


use paging\paging;

global $builtInPages;

$paging = new paging($pluginPages);


$paging->addBuiltInPage("Home", THEME_PAGES_DIR . "home.page.php", "This is a test desciption");
$paging->addBuiltInPage("About Us", THEME_PAGES_DIR . "about.page.php", "This is a test desciption");
$paging->addBuiltInPage("Posts", THEME_PAGES_DIR . "posts.page.php", "This is a test desciption");
$paging->addBuiltInPage("Blog", THEME_PAGES_DIR . "blog.page.php", "This is a test desciption");
$paging->addBuiltInPage("custom", THEME_PAGES_DIR . "customPage.page.php", "This is a test desciption");



if (!defined('DASHBOARD') || defined('THEME_EDIT')) {

    if (!defined("JOB")) {
        $theme->addModule("Menu", THEME_MODULES_DIR . "menu.mod.php");
        $theme->addModule("Landing", THEME_MODULES_DIR . "landing.mod.php");
        $theme->addModule("Footer", THEME_MODULES_DIR . "footer.mod.php");
        $theme->addModule("Presentation", THEME_MODULES_DIR . "presentation.mod.php");
        $theme->addModule("Features", THEME_MODULES_DIR . "features.mod.php");
        $theme->addModule("Start Now", THEME_MODULES_DIR . "start-now.mod.php");
        $settings = new settings();
        $settings->getSettings();

        $colorPrim = $settings->getSetting("primary-color");
        $colorPrimVar = $settings->getSetting("primary-color-variant");
        $colorSec = $settings->getSetting("secondary-color");
        $colorSecVar = $settings->getSetting("secondary-color-variant");
        $colorText= $settings->getSetting("text-color");
        $colorTextVar= $settings->getSetting("text-color-variant");
        $buttonPrim = $settings->getSetting("primary-button");
        $buttonSec = $settings->getSetting("secondary-button");
        echo "<!DOCTYPE html>";
        $theme->loadCSS('/css/theme.css');
        echo "<style id='inline-styles'> :root {";
        
        echo "--primary-color: " . $colorPrim['value'] . ";";
        echo "--primary-color-variant: " . $colorPrimVar['value'] . ";";
        echo "--secondary-color: " . $colorSec['value'];
        echo "--secondary-color-variant: " . $colorSecVar['value'] . ";";
        echo "--text-color: " . $colorText['value'] . ";";
        echo "--text-color-variant: " . $colorTextVar['value'] . ";";
        echo "--primary-button: " . $buttonPrim['value'] . ";";
        echo "--secondary-button: " . $buttonSec['value'] . ";";

        echo "}</style>";

        echo '<script src="' . HOSTNAME . '/awt-src/vendor/jQuery/jquery.min.js"></script>';
        echo '<link href="' . HOSTNAME . '/awt-src/vendor/fontawesome-free-6.4.2-web/css/all.css" rel="stylesheet"/>';
    

        if(!defined('THEME_EDIT')) $theme->loadModule("Menu");

        if (!isset($_GET['page']) || empty($_GET['page'])) $_GET['page'] = "Home";
        
        echo "<title>" . WEB_NAME ." | ". $_GET['page'] . "</title>";

        if (isset($_GET['custom'])) {
            include THEME_PAGES_DIR . "customPage.page.php";
        } else {
            $theme->loadThemePage($_GET['page']);
        }

        if(!defined('THEME_EDIT')) $theme->loadModule("Footer");
        
    }
}
