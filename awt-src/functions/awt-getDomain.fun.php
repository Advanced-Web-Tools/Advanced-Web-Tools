<?php

function getDomainName()
{


    $host = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $parse = parse_url($host);

    return $parse["scheme"] . "://" . $parse["host"] . HOSTNAME_PATH;

}
