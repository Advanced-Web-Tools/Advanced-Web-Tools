<?php

use setting\Config;

/**
 * Function getDomainName
 * @return string Returns a base domain name without requested URI.
 */
function getDomainName(): ?string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    $host = $_SERVER['HTTP_HOST'];
    return $scheme . "://" . $host . Config::getConfig("AWT", "hostname path")->getValue();

}

