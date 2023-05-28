<?php

spl_autoload_register(function ($classname) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    $class .= '.class.php';
    if (file_exists(CLASSES . $class)) {
        include_once CLASSES . $class;
    } else {
        echo "Failed to load class: " . $class . "\n. Location: " . CLASSES . $class;
    }
});
