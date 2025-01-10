<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quil Page Editor > {{title}}</title>
    <link rel="stylesheet" href="@url('awt_packages/Dashboard/views/assets/css/main.css')">
    <link rel="stylesheet" href="@resource('fontawesome-free-6.5-web/css/all.css')">
    <link rel="stylesheet" href="@assets('js/vendor/coloris/dist/coloris.min.css')">
    <link rel="stylesheet" href="@assets('css/editor.css')">
    <script src="@resource('jQuery/jquery.min.js')"></script>
    <script type="module" src="@assets('js/vendor/coloris/dist/coloris.min.js')"></script>
    <script type="module" src="@assets('js/editor/Editor.js')"></script>
    <script type="module" src="@assets('js/editor/default/blocks/DefaultBlocks.js')"></script>
    <script type="module" src="@assets('js/editor/default/options/DefaultOptions.js')"></script>
</head>
<body>
<section class="top-bar shadow">
    <div class="left">
        <button class="btn_primary" id="editor_options" title="Editor options."><i class="no_mrg fa-solid fa-bars"></i>
        </button>
        <button class="btn_primary" id="manage" title="Page properties."><i class="no_mrg fa-solid fa-gears"></i>
        </button>
        <button class="btn_primary" id="save" title="Save the page."><i class="no_mrg fa-regular fa-floppy-disk"></i>
        </button>
        @yield("top_bar.left")
    </div>
    <div class="center">
        <div class="flip-switch" title="Switch between code and visual editor.">
            <input type="checkbox" id="toggle"/>
            <label for="toggle" class="switch">
                <div class="circle"></div>
                <div class="icons">
                    <i class="fas fa-eye view-icon"></i>
                    <i class="fas fa-code code-icon"></i>
                </div>
            </label>
        </div>
        <button class="btn_secondary" id="add_block" title="Open/Hide block selector.">Add
            Block<i class="fa-solid fa-cube"></i></button>
        @yield("top_bar.center")
    </div>
    <div class="right">
        <div class="action">
            <button class="btn_secondary" id="undo" title="Undoes last change.">Undo<i
                        class="fa-solid fa-rotate-left"></i></button>
            <button class="btn_secondary" id="Redo" title="Redoes last change.">Redo<i
                        class="fa-solid fa-rotate-right"></i></button>
        </div>
        <button class="btn_secondary" id="mobile" title="Simulates mobile view. Not 100% accurate."><i
                    class="fa-solid fa-mobile-screen-button no_mrg"></i></button>
        @yield("top_bar.right")
    </div>
</section>
<section class="main">
    <aside class="left hidden">
        <div class="header">
            <i title="Hide block selector." class="action fa-solid fa-arrow-left"></i>
            <h3>Block selector</h3>
        </div>
        <div class="content">

        </div>
        @yield("aside.left")
    </aside>
    <section class="editor">
        @yield("editor")
    </section>
    <aside class="right">
        <div class="header">
            <h3>Block options</h3>
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div class="content">
            <p class="info">Select an element. To display available options</p>
        </div>
        @yield("aside.right")
    </aside>
</section>
<section class="helpers">

</section>
@yield("scripts")
<div class="helper">
    <div class="content"></div>
</div>
</body>
</html>