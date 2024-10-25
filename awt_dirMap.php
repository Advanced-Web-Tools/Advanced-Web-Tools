<?php

/**
 * Root directory of the application.
 */
const ROOT = __DIR__;

/**
 * Source directory for the awt, specifically the 'awt_src' folder.
 */
const SRC = ROOT . DIRECTORY_SEPARATOR . 'awt_src' . DIRECTORY_SEPARATOR;

/**
 * Path to the classes directory within the source folder.
 */
const CLASSES = SRC . 'classes' . DIRECTORY_SEPARATOR;

/**
 * Path to the functions directory within the source folder.
 */
const FUNCTIONS = SRC . 'functions' . DIRECTORY_SEPARATOR;

/**
 * Path to the jobs directory within the source folder.
 */
const JOBS = SRC . 'jobs' . DIRECTORY_SEPARATOR;

/**
 * Main data directory for application data, located at 'awt_data'.
 */
const DATA = ROOT . DIRECTORY_SEPARATOR . 'awt_data' . DIRECTORY_SEPARATOR;

/**
 * Cache directory within the data directory for temporary cache files.
 */
const CACHE = DATA . 'cache' . DIRECTORY_SEPARATOR;

/**
 * Uploads directory within the data directory for uploaded files.
 */
const UPLOADS = DATA . 'uploads' . DIRECTORY_SEPARATOR;

/**
 * Temporary files directory within the data directory for temporary files.
 */
const TEMP = DATA . 'temp' . DIRECTORY_SEPARATOR;

/**
 * Defines the packages directory in the root, likely for third-party or modular packages.
 */
const PACKAGES = ROOT . DIRECTORY_SEPARATOR . 'awt_packages' . DIRECTORY_SEPARATOR;
