<?php
//Info

define('WEB_NAME', "AWT Development");

define('AWT_VERSION', "23.8a");

define("CONTACT_EMAIL", "mycontact@mail.com");

//File paths
define("ROOT", __DIR__);

define("SRC", ROOT.DIRECTORY_SEPARATOR.'awt-src'.DIRECTORY_SEPARATOR);
define("ADMIN", ROOT.DIRECTORY_SEPARATOR.'awt-admin'.DIRECTORY_SEPARATOR);
define("CLASSES", SRC.'classes'.DIRECTORY_SEPARATOR);
define("FUNCTIONS", SRC.'functions'.DIRECTORY_SEPARATOR);
define("JOBS", SRC.'jobs'.DIRECTORY_SEPARATOR);

define("DATA", ROOT.DIRECTORY_SEPARATOR.'awt-data'.DIRECTORY_SEPARATOR);
define("CACHE", DATA.'cache'.DIRECTORY_SEPARATOR);
define("UPLOADS", DATA.'uploads'.DIRECTORY_SEPARATOR);
define("TEMP", DATA.'temp'.DIRECTORY_SEPARATOR);

define("CONTENT", ROOT.DIRECTORY_SEPARATOR.'awt-content'.DIRECTORY_SEPARATOR);
define("THEMES", CONTENT.'themes'.DIRECTORY_SEPARATOR);
define("PLUGINS", CONTENT.'plugins'.DIRECTORY_SEPARATOR);

require_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-autoLoader.php';
require_once SRC.'vendor'.DIRECTORY_SEPARATOR.'composer'.DIRECTORY_SEPARATOR.'autoload.php';

require_once JOBS.'awt-Settings.php';

//Safe switch
define("ACCESS_ALLOWED", 1);

define("ALL_CONFIG_LOADED", 1);

//error_reporting(0);

?>