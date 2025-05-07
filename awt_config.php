<?php
const AWT_VERSION = "24.10.4";
const PACKAGE_MAX_LOAD_TRY = 5;

const DEBUG = false;

/**
 * # WARNING: Setting this to TRUE enables remote package installations in the local environment on port 3000.
 *
 * This setting is intended for development purposes only.
 * It MUST remain FALSE in production environments to ensure security.
 *
 * In order for this to work please also set `DEBUG = true;`.
 */
const REMOTE_INSTALL_FOR_DEVS = false;
const DEV_SECRET = "";

ini_set("post_max_size", "512M");
ini_set("upload_max_filesize", "512M");
ini_set("max_execution_time", "300");
ini_set("max_input_time", "300");
ini_set('display_errors', 0);

if (DEBUG) {
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED ^ E_WARNING ^ E_NOTICE);
} else {
    error_reporting(0);
}


