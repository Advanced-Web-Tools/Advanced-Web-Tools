<?php
use paging\editor;

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php $theme->loadModule("Menu"); ?>

<div class="scene" style="overflow-x: hidden; z-index: 0; position: relative;">
    <?php
    if (isset($_GET['editPage'])) {
        $editor = new editor(array());
        $editor->loadPageEdit($_GET['editPage']);
    } else if (isset($_GET['preview'])) {
        $paging->loadPreview($_GET['preview'], $_GET['page']);
    } else {
        $paging->getPage(false);
    }
    ?>

</div>
<?php
$theme->loadModulesByOrder(THEME_DIR . "customPage.xml"); 
?>
</body>
</html>

