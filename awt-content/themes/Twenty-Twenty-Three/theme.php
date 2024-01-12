<?php

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

        $colors = $theme->retrieveCSSColors(THEME_DIR . "settings.xml");

        $theme->loadCSS('/css/theme.css');
        echo "<style id='inline-styles'> :root {";

        foreach ($colors as $key => $value) {
            echo "--$key: $value;";
        }

        echo "}</style>";

        echo '<script src="' . HOSTNAME . '"/awt-src/vendor/jQuery/jquery.min.js"></script>';
        echo '<link href="' . HOSTNAME . '/awt-src/vendor/fontawesome-free-6.4.2-web/css/all.css" rel="stylesheet">';
    

        if(!defined('THEME_EDIT')) $theme->loadModule("Menu");

        if (!isset($_GET['page']) || empty($_GET['page']))
            $_GET['page'] = "Home";

        echo "<title>" . WEB_NAME ." | ". $_GET['page'] . "</title>";

        if (isset($_GET['custom'])) {
            include THEME_PAGES_DIR . "customPage.page.php";
        } else {
            $paging->getPage(true, true, 'paging');
        }

        if(!defined('THEME_EDIT')) $theme->loadModule("Footer");
        
    }
}
