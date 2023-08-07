<?php

if (!defined("ALL_CONFIG_LOADED")) {
    include_once '../awt-config.php';
    include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
    include_once JOBS . 'awt-domainBuilder.php';
    include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
}


use admin\authentication;

$admin = new authentication;

if ($admin->checkAuthentication() && !defined('JOB') && !defined('DASHBOARD')):

include_once FUNCTIONS.'awt-navbar.fun.php';
?>

<!DOCTYPE html>
<html lang="en">
<body>
    <style>

        .floating-editor {
            background: var(--primary-color);
            padding: 20px;
            border: 1px solid var(--primary-color-variant);
            border-radius: 50% 50% 0 50%;
            position: fixed;
            top: 50%;
            left: 20px;
            z-index: 2;
            cursor: pointer;
        }

        .floating-editor img {
            display: block;
            width: 30px;
            filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(297deg) brightness(104%) contrast(103%);
        }

        .options.hidden {
            display: none;
        }

        .options {
            display: grid;
            grid-template-rows: 33px;
            grid-auto-flow: row;
            gap: 15px;
            min-height: 100px;
            margin: -60pt 55px;
            padding: 10px;
            position: absolute;
            border: 1px solid var(--primary-color-variant);
            background: var(--primary-color);
            border-radius: 10px;
            cursor: default;
        }

        .options .nav-item {
            color: var(--secondary-color);
            font-size: 15px;
            text-decoration: none;
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            width: 250px;
            gap: 10px;
            padding: 0 10px;
            align-items: center;
        }

        .options .nav-item img {
            width: 20px;
        }

    </style>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <div class="floating-editor">
        <span>
            <img src="<?php echo HOSTNAME; ?>awt-content/plugins/floatingEditor/data/icons/pen-to-square-regular.svg">
        </span>
        <div class="options hidden">
            <?php navbarLoader($navbar); ?>
        </div>
    </div>
    <script>
        $(function() {
            $(".floating-editor").first().draggable();
        });
        $(function() {
            $(".floating-editor img").click(function() {
                $(".options").toggleClass('hidden');
            });
        });
    </script>
</body>

</html>
<?php endif; ?>