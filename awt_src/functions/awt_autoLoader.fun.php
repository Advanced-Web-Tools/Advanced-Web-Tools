<?php
spl_autoload_register(function ($classname): void {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    if (file_exists(CLASSES . $class . '.php')) {
        include_once CLASSES . $class . '.php';
    } else if (file_exists(PACKAGES . $class . ".php")) {
        include_once PACKAGES . $class . '.php';
    } else {
        echo "Failed to load class: " . $class . "<br>";

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);

        foreach ($trace as $level) {
            echo "File: " . ($level['file'] ?? '[internal]') . " Line: " . ($level['line'] ?? '?') . "<br>";
            echo "Function: " . ($level['function'] ?? '[global]') . "<br><br>";
        }
        exit();
    }
});
