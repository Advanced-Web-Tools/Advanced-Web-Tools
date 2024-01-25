<?php
use themes\settings;

define("THEME_DIR", __DIR__ . DIRECTORY_SEPARATOR);
define("THEME_PAGES_DIR", THEME_DIR . "pages" . DIRECTORY_SEPARATOR);
define("THEME_MODULES_DIR", THEME_DIR . "modules" . DIRECTORY_SEPARATOR);
define("THEME_NAME", "Twenty-Twenty-Three");


use paging\{paging, renderer};

global $builtInPages;

$paging = new paging($pluginPages);


$paging->addBuiltInPage("Home", THEME_PAGES_DIR . "Home.page.php", "This is a test desciption");
$paging->addBuiltInPage("About Us", THEME_PAGES_DIR . "about.page.php", "This is a test desciption");
$paging->addBuiltInPage("Posts", THEME_PAGES_DIR . "posts.page.php", "This is a test desciption");
$paging->addBuiltInPage("Blog", THEME_PAGES_DIR . "blog.page.php", "This is a test desciption");
$paging->addBuiltInPage("custom", THEME_PAGES_DIR . "customPage.page.php", "This is a test desciption");

if (!defined('DASHBOARD') || defined('THEME_EDIT')) {

    if (!defined("JOB")) {

        if (!isset($_GET['page']) || empty($_GET['page'])) $_GET['page'] = "Home";

        $theme->addModule("Menu", THEME_MODULES_DIR . "menu.mod.php");
        $theme->addModule("Landing", THEME_MODULES_DIR . "landing.mod.php");
        $theme->addModule("Footer", THEME_MODULES_DIR . "footer.mod.php");
        $theme->addModule("Presentation", THEME_MODULES_DIR . "presentation.mod.php");
        $theme->addModule("Features", THEME_MODULES_DIR . "features.mod.php");
        $theme->addModule("Start Now", THEME_MODULES_DIR . "start-now.mod.php");

        $settings = new settings();
        $settings->getSettings();

        $description = $settings->getSetting("description")['value'];
        $separate_title_with = $settings->getSetting("separate-title-with")['value'];
        $logo = $settings->getSetting("icon-URL")['value'];
    
        $colorPrim = $settings->getSetting("primary-color");
        $colorPrimVar = $settings->getSetting("primary-color-variant");
        $colorSec = $settings->getSetting("secondary-color");
        $colorSecVar = $settings->getSetting("secondary-color-variant");
        $colorText= $settings->getSetting("text-color");
        $colorTextVar= $settings->getSetting("text-color-variant");
        $buttonPrim = $settings->getSetting("primary-button");
        $buttonSec = $settings->getSetting("secondary-button");

        $themeCSS = $theme->loadCSS('/css/theme.css');

        $render = new renderer();

        $render->addToHead($themeCSS);

        $render->addToHead('<meta name="viewport" content="width=device-width, initial-scale=1">');
        $themeColors = "<style id='inline-styles'> :root {" . "\n";
        $themeColors .=  "--primary-color: " . $colorPrim['value'] . ";" ."\n";
        $themeColors .=  "--primary-color-variant: " . $colorPrimVar['value'] . ";" ."\n";
        $themeColors .= "--secondary-color: " . $colorSec['value'];
        $themeColors .=  "--secondary-color-variant: " . $colorSecVar['value'] . ";" ."\n";
        $themeColors .=  "--text-color: " . $colorText['value'] . ";";
        $themeColors .=  "--text-color-variant: " . $colorTextVar['value'] . ";" ."\n";
        $themeColors .=  "--primary-button: " . $buttonPrim['value'] . ";" . "\n";
        $themeColors .=  "--secondary-button: " . $buttonSec['value'] . ";" . "\n"; 

        $themeColors .=  "}</style>" . "\n";
        
        $render->addToHead($themeColors);
        $render->addToHead('<script src="' . HOSTNAME . '/awt-src/vendor/jQuery/jquery.min.js"></script>');
        $render->addToHead('<link href="' . HOSTNAME . '/awt-src/vendor/fontawesome-free-6.5-web/css/all.css" rel="stylesheet"/>');
        $render->addToHead("<title>" . WEB_NAME ." $separate_title_with ". $_GET['page'] . "</title>");
        $render->addToHead('<meta name="description" content="'.$description.'">');
        $render->addToHead('<link rel="icon" type="image/x-icon" href="'. $logo .'">');

        if(!defined('THEME_EDIT')){ 
            $nav = $theme->loadModule("Menu");
            $render->addToBody($nav);
        }

        if (isset($_GET['custom'])) {
            $body = $theme->loadThemePage('custom');
            $render->addToBody($body);
        } else {
            $body = $theme->loadThemePage($_GET['page']);
            $render->addToBody($body);
        }
        
        if(!defined('THEME_EDIT')){ 
            $footer = $theme->loadModule("Footer");
            $render->addToBody($footer);
        }
        
        echo $render->renderPage();
    }
}
