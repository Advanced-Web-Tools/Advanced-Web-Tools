<?php

$enginePath = PLUGINS.'floatingNavigation'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'floatingEditor.php';
$dependenciePath = PLUGINS.'floatingNavigation'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'floatingEditor.fun.php';

global $navbar;

addDependencie('high', 'floatingNavigation', $dependenciePath, '0.0.1');

addEngine('floatingNavigation', $enginePath, '0.0.1', 'end');