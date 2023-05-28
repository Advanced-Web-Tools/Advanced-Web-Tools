<?php

use admin\authentication;
use admin\profiler;
use themes\modules;
use themes\themes;

$check = new authentication;

$css = new themes;
$colors = $css->retrieveCSSColors(THEME_DIR . "settings.xml");

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

$profiler = new profiler;

if (!$profiler->checkPermissions(0)) {
    echo "No permission";
    exit();
}

$module = new modules;
$moduleData = $module->getModuleData(THEME_DIR . $_GET['page'] . "PageModules.xml");

?>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

<script src="./javascript/themeEditor/colorEditor.js"></script>
<script src="./javascript/themeEditor/settingsSubmenu.js"></script>
<script src="./javascript/themeEditor/moduleDragger.js"></script>
<script src="./javascript/themeEditor/viewPort.js"></script>

<form>
    <?php 
        if($theme->loadGlobalSettingsPage() !== false) include_once $theme->loadGlobalSettingsPage();
        if($theme->loadSettingsPage($_GET['page']) !== false) include_once $theme->loadSettingsPage($_GET['page']);
    ?>
</form>

<script>
    openSubmenu(".colors .options-header", ".colors .options", "hidden");
    openSubmenu(".modules .options-header", ".modules .options", "hidden");
    moduleDragger(".preview", ".modules .options");
</script>