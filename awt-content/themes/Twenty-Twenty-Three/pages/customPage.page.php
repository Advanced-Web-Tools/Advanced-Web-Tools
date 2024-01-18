<?php
use paging\editor;
$theme->addModule("Menu", THEME_MODULES_DIR . "menu.mod.php");
$theme->addModule("Footer", THEME_MODULES_DIR . "footer.mod.php");
?>

<?php if (isset($_GET['editPage'])) echo $theme->loadModule("Menu"); ?>
<div class="scene" style="z-index: 0; position: relative;">
    <?php
    if (isset($_GET['editPage'])) {
        $editor = new editor(array());
        $editor->loadPageEdit($_GET['editPage']);
    } else if (isset($_GET['preview'])) {
        $paging->loadPreview($_GET['preview'], $_GET['page']);
    } else {
        $paging->getPage(true, true, "paging");
    }
    ?>
</div>
<?php if (isset($_GET['editPage'])) echo $theme->loadModule("Footer"); ?>


