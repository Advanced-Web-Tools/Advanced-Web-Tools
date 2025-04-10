<?php

namespace Theming\classes\Theme\Menu;
use database\DatabaseManager;
use DOMDocument;
use DOMException;

class ThemeMenu
{
    private DatabaseManager $database;
    private int $id;
    public string $name;

    private array $items = [];

    public function __construct()
    {
        $this->database = new DatabaseManager();

        $result = $this->database->
        table("theming_menu_item")->
        select([
            "theming_menu_item.id",
            "theming_menu_item.name",
            "theming_menu_item.link",
            "theming_menu_item.parent_item",
            "theming_menu_item.position",
            "theming_menu_item.target",
        ])->
        join("theming_menu", "theming_menu_item.menu_id = theming_menu.id")->
        orderBy('position')->
        where(["active" => "1"])->
        get();


        foreach ($result as $item) {
            $this->items[$item["position"]] = new ThemeMenuItem($item);
        }

        ksort($this->items, SORT_REGULAR);

        $this->attachChildren();
    }


    public function attachChildren(): void
    {
        foreach ($this->items as $key => $item) {
            if($item->parentId !== 0)
            {
                foreach ($this->items as $parent) {
                    if($parent->id === $item->parentId) {
                        $parent->setChild($item);
                        unset($this->items[$key]);
                    }

                }
            }
        }
    }

    public function getHTML(): string
    {
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        try {
            $nav = $dom->createElement("div");
            $nav->setAttribute("id", "nav");
            $nav->setAttribute("class", "navigation");

            foreach($this->items as $item)
            {
                $nav->appendChild($item->createNode($dom));
            }
            $dom->appendChild($nav);
        } catch (DOMException $e) {
            die($e->getMessage() . "<br>" . $e->getTraceAsString());
        }

        return $dom->saveHTML();
    }


}