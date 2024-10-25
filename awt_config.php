<?php
const AWT_VERSION = "v24.6.1";
const PACKAGE_MAX_LOAD_TRY = 5;
const DEBUG = true;

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED ^ E_WARNING ^ E_NOTICE);
} else {
    error_reporting(0);
}


