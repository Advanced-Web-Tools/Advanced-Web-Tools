<?php

namespace paging;
use database\databaseConfig;


class renderer
{
    private databaseConfig $database;
    private object $mysqli;
    public array $head;
    public array $body;
    public array $docStructure;
    public string $page;

    public function __construct()
    {

        $this->docStructure['doctype'] = "<!DOCTYPE html>";
        $this->docStructure['structure']['htmlTop'] = "<html>";
        $this->docStructure['structure']['head'] = "";
        $this->docStructure['structure']['body'] = "";
        $this->docStructure['structure']['htmlEnd'] = "</html>";

        $this->head = array();
        $this->body = array();
        $this->page = "";
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


    public function renderPage() : string
    {
        $this->addHead();
        $this->addBody();

        $this->page = $this->docStructure['doctype'];
        $this->page .= $this->docStructure['structure']['htmlTop'];
        $this->page .= $this->docStructure['structure']['head'];
        $this->page .= $this->docStructure['structure']['body'];
        $this->page .= $this->docStructure['structure']['htmlEnd'];

        $this->page = $this::sanitizePage($this->page);

        return $this->page;
    }

    public function addToHead(string $html) : void
    {
        array_push($this->head, $html);
    }

    protected function addHead() : void
    {
        $this->docStructure['structure']['head'] = "<head>";

        foreach ($this->head as $key => $element) {
            $this->docStructure['structure']['head'] .= $element;
        }

        $this->docStructure['structure']['head'] .= "</head>";

    }

    public function addToBody(string $html) : void
    {
        $this->body[] = $html;
    }

    protected function addBody() : void
    {
        $this->docStructure['structure']['body'] = "<body>";

        foreach ($this->body as $key => $element) {
            $this->docStructure['structure']['body'] .= $element;
        }
        $this->docStructure['structure']['body'] .= "</body>";

    }

}