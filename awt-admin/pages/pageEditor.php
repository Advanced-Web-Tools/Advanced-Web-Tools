<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use themes\themes;

$theme = new themes;

$theme->getThemes();

$theme->getActiveTheme();

?>
<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include jQuery UI library -->
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

<!-- Include jQuery UI CSS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
</head>
<script src="./javascript/themeEditor/hidenav.js">
</script>
<script src="./javascript/pageEditor/pageEditor.js">
</script>
<link rel="stylesheet" href="./css/pageEditor.css">

<div class="dialog">

</div>

<section class="editor">
    <div class="top-menu">
        <div class="action-buttons">
            <button type="button" onclick="publishContent('<?php echo $_GET['pageName'];?>');">Publish</button>
            <button type="button" onclick="publishContentPreview('<?php echo $_GET['pageName'];?>');">Preview</button>
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