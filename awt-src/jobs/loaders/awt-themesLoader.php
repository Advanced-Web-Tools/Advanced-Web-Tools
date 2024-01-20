<?php

if(defined('ALL_CONFIG_LOADED')) {
    $theme = new themes\themes; 
} else {
    echo 'Error code 1';
    exit();
}
echo $theme->loadTheme();
