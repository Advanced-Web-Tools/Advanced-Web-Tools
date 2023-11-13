<?php

if (!defined("THEME_NAME")) {
    define("THEME_NAME", "Twenty-Twenty-Three");
    define("THEME_DIR", THEMES . THEME_NAME . DIRECTORY_SEPARATOR);
    define("THEME_PAGES_DIR", THEME_DIR . "pages" . DIRECTORY_SEPARATOR);
    define("THEME_MODULES_DIR", THEME_DIR . "modules" . DIRECTORY_SEPARATOR);
    $theme->addModule("Menu", THEME_MODULES_DIR . "menu.mod.php");
    $theme->addModule("Landing", THEME_MODULES_DIR . "landing.mod.php");
    $theme->addModule("Footer", THEME_MODULES_DIR . "footer.mod.php");
    $theme->addModule("Presentation", THEME_MODULES_DIR . "presentation.mod.php");
    $theme->addModule("Features", THEME_MODULES_DIR . "features.mod.php");
    $theme->addModule("Start Now", THEME_MODULES_DIR . "start-now.mod.php");
    $colors = $theme->retrieveCSSColors(THEME_DIR . "settings.xml");
    $theme->loadCSS('/css/theme.css');
}


?>

<?php $theme->loadModule("Menu"); ?>

<div class="scene" style="overflow-x: hidden; z-index: 0; position: relative;">
    <?php
    if (isset($_GET['editPage'])) {
        $paging->loadPageEdit($_GET['editPage']);
    } else if (isset($_GET['preview'])) {
        $paging->loadPreview($_GET['preview'], $_GET['page']);
    } else {
        $paging->loadPage($_GET['page'], "name");
    }
    ?>

</div>
<?php
$theme->loadModulesByOrder(THEME_DIR . "customPage.xml"); 
?>
