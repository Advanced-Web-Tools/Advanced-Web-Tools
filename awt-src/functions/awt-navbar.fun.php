<?php

function navbarLoader($navbar)
{   
    if (isset($navbar['end'])) {
        $end[] = $navbar['end'];
        unset($navbar['end']);
    }

    if (isset($navbar['last'])) {
        $last[] = $navbar['last'];
        unset($navbar['last']);
    }

    foreach ($navbar as $nav) {
        $nav->writeItems();
    }

    if(isset($end)) {
        foreach ($end as $nav) {
            $nav->writeItems();
        }
    }

    if(isset($last)) {
        foreach ($last as $nav) {
            $nav->writeItems();
        }
    }

}