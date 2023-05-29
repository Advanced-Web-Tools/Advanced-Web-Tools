<?php

use admin\navbar;

$nav = new navbar;
$logout = new navbar;
$settings = new navbar;

if (defined('DASHBOARD') || checkForPlugin('floatingEditor', '0.0.1') == true) {

    $location = HOSTNAME . 'awt-content/plugins/defaultDashboardNavigation/data/icons/';
    $nav->addItem(array('icon' => $location . 'house-solid.svg', 'name' => 'Dashboard', 'link' => HOSTNAME . 'awt-admin/?page=Dashboard', 'permission' => 2));
    $nav->addItem(array('icon' => $location . 'file-lines-solid.svg', 'name' => 'Pages', 'link' => HOSTNAME . 'awt-admin/?page=Pages', 'permission' => 2));
    $nav->addItem(array('icon' => $location . 'square-plus-regular.svg', 'name' => 'Posts', 'link' => HOSTNAME . 'awt-admin/?page=Posts', 'permission' => 2));
    $nav->addItem(array('icon' => $location . 'brush-solid.svg', 'name' => 'Themes', 'link' => HOSTNAME . 'awt-admin/?page=Themes', 'permission' => 0));
    $nav->addItem(array('icon' => $location . 'puzzle-piece-solid.svg', 'name' => 'Plugins', 'link' => HOSTNAME . 'awt-admin/?page=Plugins', 'permission' => 0));
    $nav->addItem(array('icon' => $location . 'store-solid.svg', 'name' => 'Store', 'link' => HOSTNAME . 'awt-admin/?page=Store', 'permission' => 0));
    $nav->addItem(array('icon' => $location . 'sliders-solid.svg', 'name' => "Theme Editor", 'link' => HOSTNAME . 'awt-admin/?page=ThemeEditor', 'permission' => 0, 'attr' => 'target="_blank"'));
    $nav->addItem(array('icon' => $location . 'users-solid.svg', 'name' => 'Accounts', 'link' => HOSTNAME . 'awt-admin/?page=Accounts', 'permission' => 0));
    $settings->addItem(array('icon' => $location . 'toolbox-solid.svg', 'name' => 'Settings', 'link' => HOSTNAME . 'awt-admin/?page=Settings', 'permission' => 0));
    $logout->addItem(array('icon' => $location . 'exit-solid.svg', 'name' => 'Log out', 'link' => HOSTNAME . 'awt-admin/jobs/signInOut.php?logout', 'permission' => 2));

    array_push($navbar, $nav);
    $navbar['end'] = $settings;
    $navbar['last'] = $logout;
}
?>
<?php if (defined('DASHBOARD')) : ?>
    <script src="../awt-src/vendor/jQuery/jquery.min.js"></script>
    <script>
        window.addEventListener("load", (event) => {
            var page = '<?php echo HOSTNAME . 'awt-admin/?page='.$_GET['page']; ?>';
            $('.nav-item[href="' + page + '"]').addClass('current');
           
        });
    </script>
<?php endif; ?>