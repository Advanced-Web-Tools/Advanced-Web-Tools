<?php

function pushToWidgets(array $widget)
{
    global $dashboardWidgets;

    $dashboardWidgets[$widget["name"]]["src"] = $widget["src"];
}


function loadAllWidgets()
{
    global $dashboardWidgets;

    foreach ($dashboardWidgets as $key => $value) {
        if (file_exists($value["src"])) include_once $value["src"];
    }
}
