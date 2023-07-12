<script src="./javascript/themeEditor/hidenav.js">
</script>
<script src="./javascript/themeEditor/saveChanges.js"></script>
<link rel="stylesheet" href="./css/themeEditor.css">
<div class="preview">
    <?php

    define('THEME_EDIT', 1);

    if (!isset($_GET['theme_page'])) {
        $_GET['page'] = 'Home';
    } else {
        $_GET['page'] = $_GET['theme_page'];
    }

    include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-themesLoader.php';
    ?>
</div>
<div class="settings">
    <div class="actions">
        <button type="button" onclick="saveChanges('<?php echo $_GET['page']; ?>')">Save</button>
    </div>
    <?php include_once THEME_DIR . "settings.php"; ?>
</div>