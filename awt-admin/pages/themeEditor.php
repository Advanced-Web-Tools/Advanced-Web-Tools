<?php
defined('ALL_CONFIG_LOADED') or die("An error has occured");
define('THEME_EDIT', 1);
use admin\authentication;


$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

if (!isset($_GET['theme_page'])) {
    $_GET['page'] = 'Home';
} else {
    $_GET['page'] = $_GET['theme_page'];
}

?>
<link rel="stylesheet" href="./css/themeEditor.css">
<link rel="stylesheet" href="./css/pageEditor.css">
<script src="./javascript/themeEditor/editor.js"></script>
<!-- <div class="textEditor hidden shadow">
    <div class="header">
        <p onclick="$(this).parent().parent().addClass('hidden')"><i class="fa-regular fa-circle-xmark"></i>
        </p>
    </div>
    <div class="text-options">
        <button type="button" class="button" id="addHyperLink"><i class="fa-solid fa-anchor"></i></button>
        <button type="button" class="button" id="turnItalic"><i class="fa-solid fa-italic"></i></button>
        <button type="button" class="button" id="turnBold"><i class="fa-solid fa-bold"></i></button>
        <button type="button" class="button" id="breakLine"><i class="fa-solid fa-diagram-successor"></i></button>
    </div>
</div> -->

<div class="context-menu shadow hidden">
    <p>Quick options</p>
    <div class="context-group">
        <button class="button" onclick="saveThemePage('<?php echo $_GET['page']; ?>');"><i
                class="fa-regular fa-floppy-disk"></i></button>
        <button class="button" onclick="$('.selected').remove()"><i class="fa-solid fa-trash"></i></button>
    </div>
    <button class="button" onclick="copy()">Copy <i class="fa-regular fa-copy"></i></button>
    <button class="button" onclick="paste()">Paste <i class="fa-regular fa-paste"></i></button>
    <button class="button" onclick="cut()">Cut <i class="fa-solid fa-scissors"></i></button>
    <button class="button" onclick="undo()">Undo <i class="fa-solid fa-rotate-left"></i></button>
    <button class="button" onclick="redo()">Redo <i class="fa-solid fa-rotate-right"></i></button>
</div>

<div class="dialog shadow">
    <div class="header">
        <p onclick="$(this).parent().parent().toggleClass('active')"><i class="fa-regular fa-circle-xmark"></i></p>
    </div>
    <div class="content">

    </div>
</div>

<div class="top-menu">
    <div class="action-buttons">
        <button type="button" onclick="saveThemePage('<?php echo $_GET['page']; ?>');" class="button"
            id="green">Save</button>
    </div>
</div>

<div class="stage">
    <!-- <div class="add-blocks hidden">
        <div class='blocks-header'>
            <p onclick="$('.add-blocks ').toggleClass('hidden')"><i class="fa-regular fa-circle-xmark"></i></p>
        </div>
    </div> -->
    <div class="preview">
        <div class="textEditor hidden shadow">
            <div class="header">
                <p onclick="$(this).parent().parent().addClass('hidden')"><i class="fa-regular fa-circle-xmark"></i>
                </p>
            </div>
            <div class="text-options">
                <button type="button" class="button" id="addHyperLink"><i class="fa-solid fa-anchor"></i></button>
                <button type="button" class="button" id="turnItalic"><i class="fa-solid fa-italic"></i></button>
                <button type="button" class="button" id="turnBold"><i class="fa-solid fa-bold"></i></button>
                <button type="button" class="button" id="breakLine"><i
                        class="fa-solid fa-diagram-successor"></i></button>
            </div>
        </div>
        <?php
        include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-themesLoader.php';include_once JOBS . 'jobs' . DIRECTORY_SEPARATOR . 'awt-themesLoader';
        ?>
    </div>
    <div class="editor-tools">
        <div class="block-options">
            <p>Select an element to edit.</p>
        </div>
    </div>
</div>

<script src="../awt-src/vendor/jQuery/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="./javascript/pages/hidenav.js">
</script>
<script src="./javascript/pageEditor/blockOptions.js">
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
<script src="./javascript/pageEditor/shortcuts.js"></script>
<script src="./javascript/pageEditor/clipboard.js"></script>
<script src="./javascript/pageEditor/history.js"></script>
<script src="./javascript/menus/menus.js"></script>
<script src="../awt-src/vendor/jQuery/jquery.nearest.min.js"></script>
<script>
    $(document).ready(function () {

        var find = $('*').filter(function () {
            return $(this).css('position') == 'fixed';
        });

        find.each(function () {
            $(this).css("width", "90%");
            $(this).css("top", "6%");
        })
    });

</script>