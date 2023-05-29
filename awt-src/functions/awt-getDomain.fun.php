<?php

function getDomainName()
{

    $host = $_SERVER['HTTP_HOST'];
    $parse = parse_url($host);

    $mode = 'http://';

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'on') {

        $mode = 'https://';
    }

    if(defined('HOSTNAME_PATH'))
    {
        $host .= HOSTNAME_PATH;
    }

    return $mode.$host;


}
