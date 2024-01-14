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


    public static function sanitizePage(string $page) : string
    {
        $page = str_replace("ui-sortable-handle", "", $page);
        $page = str_replace("ui-sortable", "", $page);
        $page = str_replace("ui-sortable", "", $page);
        $page = str_replace('contenteditable="true"', "", $page);
        $page = str_replace('selected', "", $page);

        return $page;
    }



}