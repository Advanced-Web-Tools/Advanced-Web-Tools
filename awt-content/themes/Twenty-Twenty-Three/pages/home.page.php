<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $paging->pages['Home']['description'];?>">
    <title><?php echo WEB_NAME." | Home"; ?></title>
</head>

<body>
    <?php $theme->loadModule("Menu"); ?>
    <?php $theme->loadModulesByOrder(THEME_DIR.'homePageModules.xml'); ?>
</body>

</html>