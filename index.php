<?php
if (!file_exists('./awt-config.php')) {
   echo "ERROR";
   exit();
}

require_once './awt-config.php';

use admin\authentication;

$admin = new authentication;

//Crucial jobs

require_once JOBS.'awt-domainBuilder.php';

require_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-pluginLoader.php';

require_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-themesLoader.php';

enginesLoader('end');

?>