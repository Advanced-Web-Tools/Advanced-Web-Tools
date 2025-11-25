<?php
spl_autoload_register(function ($classname): void {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    if (file_exists(CLASSES . $class . '.php')) {
        include_once CLASSES . $class . '.php';
    } else if (file_exists(PACKAGES . $class . ".php")) {
        include_once PACKAGES . $class . '.php';
    } else {
        echo "Failed to load class: " . $class . "\n";
        exit();
    }
});
