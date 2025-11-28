<?php

namespace
Quil\classes\page;

use admin\Admin;
use database\DatabaseManager;
use Quil\classes\page\models\collections\QuilPageCollection;
use Quil\classes\page\models\collections\QuilPageRouteCollection;
use Quil\classes\page\models\QuilPage;
use Quil\classes\page\models\QuilPageContent;
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

        $page = new QuilPage(null);


        $this->createRoute("/view/$name");

        $page->route_id = $this->routeId;
        $page->name = $name;
        $page->created_by = $this->admin->getParam("id");

        $this->pageId = $page->saveModel();

        $content = new QuilPageContent(null);
        $content->page_id = $this->pageId;
        $content->content = "<div class='page'><h1 class='block'>Hello world!</h1></div>";
        $this->contentId = $content->saveModel();
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
        $page = new QuilPage($id);
        if($page->route !== null)
            $page->route->deleteModel();

        $page->deleteModel();
    }

    public function deleteRoute(int $id): void
    {
        (new QuilPageRoute($id))->deleteModel();
    }

    public function createRoute(string $path): void
    {
        $route = str_replace(" ", "", $path);

        $route = new QuilPageRoute(["id" => null ,"route" => $route, "created_by" => $this->admin->getParam("id")]);;
        $this->routeId = $route->saveModel();
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

        $page = new QuilPage($id);

        if(isset($params["name"])) {
            $page->name = $params["name"];
            $ret = $page->save();
            if(!$ret)
                return false;
        }


        if(isset($params["route_id"])) {
            $routeId = $params["route_id"];

            if($routeId === "null") {
                $routeId = null;
            }
            $page->route_id = $routeId;
            $ret = $page->save();
            if(!$ret)
                return false;
        }


        if(isset($params["description"])) {
            $description = $params["description"];

            $page->description = $description;

            $ret = $page->save();
            if(!$ret)
                return false;
        }

        $page->__destruct();


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