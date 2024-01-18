<?php 
use menu\menu;

$menu = new menu;

$items = $menu->retrieveMenuItems();

define("MENU_ITEMS", $items);

echo $theme->loadCSS('/css/menu.css');

?>
<script src="<?php echo $theme->getAssetLink('/javascript/menu/menu.js');?>"></script>
<nav class="main-navigation">
    <div class="hamburger-menu">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <ul>
        <?php foreach ($items as $key => $value): ?>
            <li><?php echo $value; ?></li>
        <?php endforeach;?>
    </ul>
</nav>