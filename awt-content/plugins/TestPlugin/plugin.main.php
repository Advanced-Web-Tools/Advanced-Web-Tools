<?php

use admin\navbar;
$test = new navbar;
$name = 'testPlugin';
$version = "0.0.1";

$pluginPages['testPlugin'] = PLUGINS.$name.DIRECTORY_SEPARATOR.'plugin.page.php';
addEngine('testPlugin', PLUGINS.$name.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'test.php', $version);

if(function_exists('navbarLoader')) {
    $location = HOSTNAME.'awt-content/plugins/TestPlugin/data/icons/';

    $test->addItem(array('icon' => $location.'flask-vial-solid.svg', 'name'=>'Test Plugin', 'link' => '?page=testPlugin'));

    array_push($navbar, $test);
}

