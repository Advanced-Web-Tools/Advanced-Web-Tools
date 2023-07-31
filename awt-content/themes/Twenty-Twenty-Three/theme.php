<?php

define("THEME_DIR", __DIR__ . DIRECTORY_SEPARATOR);
define("THEME_PAGES_DIR", THEME_DIR . "pages" . DIRECTORY_SEPARATOR);
define("THEME_MODULES_DIR", THEME_DIR . "modules" . DIRECTORY_SEPARATOR);
define("THEME_NAME", "Twenty-Twenty-Three");


use paging\paging;


$paging = new paging($pluginPages);


$paging->addBuiltInPage("Home", THEME_PAGES_DIR . "home.page.php", "This is a test desciption");
$paging->addBuiltInPage("About Us", THEME_PAGES_DIR . "about.page.php", "This is a test desciption");
$paging->addBuiltInPage("custom", THEME_PAGES_DIR . "customPage.page.php", "This is a test desciption");

$theme->addModule("Menu", THEME_MODULES_DIR . "menu.mod.php");
$theme->addModule("Landing", THEME_MODULES_DIR . "landing.mod.php");
$theme->addModule("Footer", THEME_MODULES_DIR . "footer.mod.php");
$theme->addModule("Presentation", THEME_MODULES_DIR . "presentation.mod.php");
$theme->addModule("Features", THEME_MODULES_DIR . "features.mod.php");
$theme->addModule("Start Now", THEME_MODULES_DIR . "start-now.mod.php");

$theme->addSettingsPage('global', THEME_DIR . 'pageSettings' . DIRECTORY_SEPARATOR . "globalSettings.php", true);
$theme->addSettingsPage('Home', THEME_DIR . 'pageSettings' . DIRECTORY_SEPARATOR . "homePageModules.php");

$colors = $theme->retrieveCSSColors(THEME_DIR . "settings.xml");

$theme->loadCSS('/css/theme.css');

if (!defined('DASHBOARD') || defined('THEME_EDIT')) {
    echo "<style id='inline-styles'> :root {";

    foreach ($colors as $key => $value) {
        echo "--$key: $value;";
    }

    echo "}</style>";

    echo '<script src="./awt-src/vendor/jQuery/jquery.min.js"></script>';
    echo '<script src="https://kit.fontawesome.com/9623f60d76.js" crossorigin="anonymous"></script>';
    if (!isset($_GET['page']) || empty($_GET['page'])) $_GET['page'] = "Home";

    if (isset($_GET['custom'])) {
        include THEME_PAGES_DIR . "customPage.page.php";
    } else {
        $paging->getPage(true, 'paging');
    }
}
