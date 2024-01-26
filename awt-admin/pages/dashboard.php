<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\authentication;

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

?>
<link rel="stylesheet" href="./css/dashboard.css">
<div class="welcome-screen shadow hidden">
    <div class="welcome-header">
        <h1>Welcome to AWT Dashboard</h1>
        <i class="fa-solid fa-x"></i>
        <p>Here are some tips to get you started!</p>
    </div>
    <div class="content">
        <div class="wrapper" style="gap: 20px;">
            <h3>Get started</h3>
            <a href="?page=Customize"><button type="button" class="button">Customize Your Site</button></a>
            <p>or <a href="?page=Store" class="link"> install new theme.</a></p>
        </div>
        <div class="wrapper">
            <h3>Next</h3>
            <a href="?page=Theme Editor" target="_blank" class="link"><i class="fa-regular fa-pen-to-square"></i> Edit
            your landing page</a>
            <a href="?page=Menus" class="link"><i class="fa-solid fa-compass"></i> Edit menu.</a>
            <a href="?page=Pages" class="link"><i class="fa-solid fa-plus"></i> Create more pages</a>
        </div>
        <div class="wrapper">
            <h3>Optional</h3>
            <a href="?page=Accounts" class="link"><i class="fa-solid fa-users"></i> Create new users</a>
            <a href="?page=Media" class="link"><i class="fa-solid fa-upload"></i> Upload images</a>
            <a href="?page=Plugins" class="link"><i class="fa-solid fa-puzzle-piece"></i> Install plugins</a>
        </div>
        <div class="wrapper">
            <h3>Populate your dashboard</h3>
            <a href="?page=Store&viewStore=AWT Dashboard Widgets" class="link"><i class="fa-solid fa-shapes"></i>
            Install default widgets plugin</a>
        </div>
    </div>
</div>
<div class="attention-screen shadow">
    <h4>Attention needed:</h4>
    <div class="attention-list">
        <p>All is good!</p>
    </div>
</div>
<div class="widgets">
    <?php loadAllWidgets(); ?>
</div>
<script src="./javascript/attention/attention.js"></script>
<script>
    const screen = $(".welcome-screen");

    var hideWelcome = localStorage.getItem("hide_welcome");

    hideWelcome = JSON.parse(hideWelcome);

    if (hideWelcome === true) {
        // screen.addClass("hidden");
    } else {
        screen.removeClass("hidden");
        hideWelcome = false;

        localStorage.setItem("hide_welcome", hideWelcome);

        $(".welcome-screen .welcome-header i").click(function () {
            screen.addClass("hidden");
            hideWelcome = true;
            localStorage.setItem("hide_welcome", hideWelcome);
        });

    }

    // $(document).ready(function () {
    //     const close_button = $('<i class="fa-solid fa-x"></i>');

    //     var widgets = $(".widgets").children();

    //     widgets.each(function (index, element) {
    //         const button = close_button.clone();
    //         $(element).prepend(button);
    //     });
    // });



</script>