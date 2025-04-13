<?php

use render\TemplateEngine\BladeOne;
function handle_error(): void
{
    $last = error_get_last();
    if($last['type'] === E_ERROR) {
        $engine = new BladeOne(ROOT, CACHE, BladeOne::MODE_SLOW);

        $data["error"] = $last['message'];

        $engine->setFileExtension(".awt.php");
        die($engine->run("error", $data));
    }
}

register_shutdown_function("handle_error");