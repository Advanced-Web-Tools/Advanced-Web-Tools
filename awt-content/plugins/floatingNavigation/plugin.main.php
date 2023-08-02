<?php

$enginePath = PLUGINS.'floatingEditor'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'floatingEditor.php';
$dependenciePath = PLUGINS.'floatingEditor'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'floatingEditor.fun.php';

global $navbar;

addDependencie('high', 'floatingEditor', $dependenciePath, '0.0.1');

addEngine('floatingEditor', $enginePath, '0.0.1', 'end');