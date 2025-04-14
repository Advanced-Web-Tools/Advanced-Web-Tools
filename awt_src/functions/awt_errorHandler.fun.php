<?php

use render\TemplateEngine\BladeOne;

function handle_fatal(): void
{
    $last = error_get_last();
    if ($last && $last['type'] === E_ERROR) {
        $engine = new BladeOne(ROOT, CACHE, BladeOne::MODE_SLOW);
        $engine->setFileExtension(".awt.php");

        $data = DEBUG ? ["error" => $last['message']] : [];

        echo $engine->run("error", $data);
        exit;
    }
}

register_shutdown_function("handle_fatal");

