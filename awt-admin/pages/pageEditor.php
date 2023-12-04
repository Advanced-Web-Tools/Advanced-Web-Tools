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

<link rel="stylesheet" href="./css/pageEditor.css">

<div class="floating-blocks hidden">
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
            <button type="button" onclick="publishContentPreview('<?php echo $_GET['pageName']; ?>');"
                class="button">Preview</button>
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