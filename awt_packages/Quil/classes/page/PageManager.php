<?php

namespace
Quil\classes\page;

use admin\Admin;
use database\DatabaseManager;
use Quil\classes\page\models\collections\QuilPageCollection;
use Quil\classes\page\models\collections\QuilPageRouteCollection;
use Quil\classes\page\models\QuilPage;
use Quil\classes\page\models\QuilPageRoute;

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
    private ?array $pages = null;
    private ?array $routes = null;
    private Admin $admin;
    public int $routeId;
    public int $pageId;
    public int $contentId;

    public function __construct()
    {
        $this->database = new DatabaseManager();
        $this->admin = new Admin();
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

        $this->contentId = $this->database->table("quil_page_content")
            ->insert([
                "page_id" => $this->pageId,
                "content" => "<div class='page'></div>"
            ])
            ->executeInsert();
    }


    public function fetchPages(): self
    {
        $collection = new QuilPageCollection();
        $this->pages = $collection->obCollection->toArray();

        return $this;
    }

    public function fetchRoutes(): self
    {

        $routes = new QuilPageRouteCollection();
        $this->routes = $routes->obCollection->toArray();

        return $this;
    }


    public function returnPages(): ?array
    {
        return $this->pages;
    }

    public function returnRoutes(): ?array
    {
        return $this->routes;
    }

    public function deletePage(int $id): void
    {
        $this->database->table("quil_page")->where(["id" => $id])->delete();
    }

    public function deleteRoute(int $id): void
    {
        $this->database->table("quil_page_route")->where(["id" => $id])->delete();
    }

    public function createRoute(string $path): void
    {
        $route = str_replace(" ", "", $path);
        $this->routeId = $this->database
            ->table("quil_page_route")
            ->insert([
                "route" => $route,
                "created_by" => $this->admin->getParam("id")
            ])
            ->executeInsert();
    }

    public function savePage(array $params): bool
    {
        $id = null;
        $name = null;
        $routeId = null;
        $description = null;
        $content = null;

        if(!isset($params["id"])) {
            return false;
        }

        $id = $params["id"];

        if(isset($params["name"])) {
            $name = $params["name"];
            $ret = $this->database->table("quil_page")->where(["id" => $id])->update([
                "name" => $name
            ]);

            if(!$ret)
                return false;
        }


        if(isset($params["route_id"])) {
            $routeId = $params["route_id"];

            if($routeId === "null") {
                $routeId = null;
            }

            $ret = $this->database->table("quil_page")->where(["id" => $id])->update([
                "route_id" => $routeId
            ]);

            if(!$ret)
                return false;
        }


        if(isset($params["description"])) {
            $description = $params["description"];

            $ret = $this->database->table("quil_page")->where(["id" => $id])->update([
                "description" => $description
            ]);
            if(!$ret)
                return false;
        }


        if(isset($params["content"])) {
            $content = $params["content"];
            $ret = $this->database->table("quil_page_content")->where(["page_id" => $id])->update([
                "content" => $content
            ]);
            if(!$ret)
                return false;
        }

        return true;

    }

}