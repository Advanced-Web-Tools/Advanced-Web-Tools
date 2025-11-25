<?php
const AWT_VERSION = "24.10.6";
const PACKAGE_MAX_LOAD_TRY = 5;

const DEBUG = false;

const SHOW_SQL_CONNECTIONS_CALLS = false;

/**
 * # WARNING: Setting this to TRUE enables remote package installations.
 *
 * This setting is intended for development purposes only.
 * It MUST remain FALSE in production environments to ensure security.
 *
 * In order for this to work please also set `DEBUG = true;`.
 */
const REMOTE_INSTALL_FOR_DEVS = false;
const DEV_SECRET = "12345678";

ini_set("post_max_size", "512M");
ini_set("upload_max_filesize", "512M");
ini_set("max_execution_time", "300");
ini_set("max_input_time", "300");

if (DEBUG) {
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED ^ E_WARNING ^ E_NOTICE);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}


