<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use themes\themes;

$theme = new themes;

$theme->getThemes();

$theme->getActiveTheme();

?>
<script src="../awt-src/vendor/jQuery/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="./javascript/themeEditor/hidenav.js">
</script>
<script src="./javascript/pageEditor/pageEditor.js">
</script>
<script src="./javascript/pageEditor/defaultOptions.js">
</script>
<script src="./javascript/pageEditor/textOptions.js">
</script>
<script src="./javascript/pageEditor/listOptions.js">
</script>
<script src="./javascript/pageEditor/mediaOptions.js">
</script>
<script src="./javascript/pageEditor/gridOptions.js">
</script>
<script src="../awt-src/vendor/jQuery/jquery.nearest.min.js"></script>

<link rel="stylesheet" href="./css/pageEditor.css">

<div class="context-menu shadow hidden">
    <p>Quick options</p>
    <div class="context-group">
        <button class="button" onclick="savePage('<?php echo $_GET['pageName']; ?>');"><i
                class="fa-regular fa-floppy-disk"></i></button>
        <button class="button" onclick="publishContent('<?php echo $_GET['pageName']; ?>');"><i
                class="fa-solid fa-upload"></i></button>
        <button class="button" onclick="$('.selected').remove()"><i class="fa-solid fa-trash"></i></button>
        <button class="button" onclick="BlockOptions($('.selected').prev())"><i
                class="fa-solid fa-left-long"></i></button>
        <button class="button" onclick="BlockOptions($('.selected').parent())"><i
                class="fa-solid fa-up-long"></i></button>
        <button class="button" onclick="BlockOptions($('.selected').children().first())"><i
                class="fa-solid fa-down-long"></i></button>
        <button class="button" onclick="BlockOptions($('.selected').next())"><i
                class="fa-solid fa-right-long"></i></button>
    </div>
    <button class="button" onclick="copy()">Copy <i class="fa-regular fa-copy"></i></button>
    <button class="button" onclick="paste()">Paste <i class="fa-regular fa-paste"></i></button>
    <button class="button" onclick="cut()">Cut <i class="fa-solid fa-scissors"></i></button>
    <button class="button">Undo <i class="fa-solid fa-rotate-left"></i></button>
    <button class="button">Redo <i class="fa-solid fa-rotate-right"></i></button>
</div>

<div class="floating-blocks hidden shadow">
    <div class="header">
        <p onclick="$(this).parent().parent().addClass('hidden')"><i class="fa-regular fa-circle-xmark"></i></p>
    </div>
    <div class="block-container">

    </div>
</div>
<div class="dialog">

</div>

<section class="editor">
    <div class="top-menu">
        <div class="action-buttons">
            <button type="button" onclick="publishContent('<?php echo $_GET['pageName']; ?>');"
                class="button">Publish</button>
            <button type="button" onclick="savePage('<?php echo $_GET['pageName']; ?>');" class="button">Save</button>
        </div>
        <div class="viewport-actions">
            <i onclick="changeViewPort(this)" class="fa-solid fa-mobile-button"></i>
        </div>
    </div>
    <div class="stage">
        <div class="preview">
            <?php
            $theme->loadThemePage("customPage.page");
            ?>
        </div>
        <div class="editor-tools">
            <div class="block-options">
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $('a').on('click', function (e) {
            $(this).prop('disabled', true);
            e.preventDefault();
        });
    });
</script>