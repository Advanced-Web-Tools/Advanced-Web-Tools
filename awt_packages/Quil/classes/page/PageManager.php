<?php

namespace
Quil\classes\page;

use admin\Admin;
use database\DatabaseManager;
use Quil\classes\page\models\PageInfo;

/**
 * Class PageManager
 *
 * - Part of `Quil` package.
 * - Use with `Dashboard` package.
 *
 * Controls custom-built pages.
 */
class PageManager
{
    private DatabaseManager $database;
    private array $pages;
    private Admin $admin;
    private int $routeId;
    private int $pageId;

    public function __construct()
    {
        $this->database = new DatabaseManager();
        $this->admin = new Admin();
        $this->pages = [];
    }

    public function createPage(string $name): void
    {
        if (trim($name) === "") {
            $name = "Page";
        }

        $this->createRoute("/view/$name");

        $this->pageId = $this->database->table("quil_page")
            ->insert([
                "route_id" => $this->routeId,
                "created_by" => $this->admin->getParam("id"),
                "name" => $name
            ])
            ->executeInsert();

        $this->database->table("quil_page_content")
            ->insert([
                "page_id" => $this->pageId,
            ])
            ->executeInsert();
    }


    public function fetchPages(): self
    {
        $result = $this->database->table('quil_page')
            ->select(["id"])
            ->where(["1" => 1])
            ->get();

        foreach ($result as $page) {
            $this->pages[] = new PageInfo($page["id"]);
        }

        return $this;
    }

    public function returnPages(): array
    {
        return $this->pages;
    }

    public function deletePage(int $id): void
    {
        $this->database->table("quil_page")->where(["id" => $id])->delete();
    }

    private function createRoute(string $path): void
    {
        $this->routeId = $this->database
            ->table("quil_page_route")
            ->insert([
                "route" => $path,
                "created_by" => $this->admin->getParam("id")
            ])
            ->executeInsert();
    }

}