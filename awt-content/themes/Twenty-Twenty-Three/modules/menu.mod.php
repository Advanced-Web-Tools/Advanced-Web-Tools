<?php 
use menu\menu;

$menu = new menu;

$items = $menu->retrieveMenuItems();

define("MENU_ITEMS", $items);

echo $theme->loadCSS('/css/menu.css');


use themes\settings;

$settings = new settings();

$settings->getSettings();

$logoURL = $settings->getSetting("logo-URL")['value'];
$logoText = $settings->getSetting("logo-text")['value'];
$branding = $settings->getSetting("branding-enabled")['value'];

if($branding == "false") {
    $branding = false;
} else {
    $branding = true;
}

?>
<script src="<?php echo $theme->getAssetLink('/javascript/menu/menu.js');?>"></script>
<nav class="main-navigation">
    <div class="hamburger-menu">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <?php if($branding): ?>
    <a class="logo-wrapper" href="<?php echo HOSTNAME ?>">
        <?php if($logoURL !== "/"): ?>
            <img src="<?php echo $logoURL ?>" alt="<?php echo $logoText ?>">
        <?php else: ?>
            <h2><?php echo $logoText ?></h2>
        <?php endif; ?>
    </a>
    <?php endif; ?>
    <ul>
        <?php foreach ($items as $key => $value): ?>
            <li><?php echo $value; ?></li>
        <?php endforeach;?>
    </ul>
</nav>