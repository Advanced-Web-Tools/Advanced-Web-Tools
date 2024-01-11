<?php

namespace paging;
use database\databaseConfig;


class renderer extends paging
{
    private databaseConfig $database;
    private object $mysqli;

    public function __construct(array $pluginPages)
    {
        parent::__construct($pluginPages);

        

    }



}