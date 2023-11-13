<?php
if (!file_exists('./awt-config.php')) {
   echo "ERROR";
   exit();
}


require_once './awt-config.php';


//Crucial jobs

require_once JOBS.'awt-domainBuilder.php';
require_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-pluginLoader.php';


use admin\authentication;

$admin = new authentication;


require_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-themesLoader.php';

enginesLoader('end');

?>