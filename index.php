<?php
if (!file_exists('./awt-config.php')) {
   echo "ERROR";
   exit();
}

require_once './awt-config.php';


//Crucial jobs

include_once JOBS.'awt-domainBuilder.php';
include_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-pluginLoader.php';

use admin\authentication;

$admin = new authentication;


include_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-themesLoader.php';

enginesLoader('end');

?>