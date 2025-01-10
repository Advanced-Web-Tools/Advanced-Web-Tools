<?php

use controller\Controller;
use database\DatabaseManager;
use Quil\classes\page\PageManager;
use Quil\classes\sources\models\DummySourceModel;
use Quil\classes\sources\SourceManager;
use view\View;

class CustomPageController extends Controller
{

    private DatabaseManager $database;
    public ?array $page = [];

    private function getPage(): void
    {
        $this->database = new DatabaseManager();

        $pageManager = new PageManager();
        $routes = $pageManager->fetchRoutes()->returnRoutes();
        $path = $_SERVER['REQUEST_URI'];

        $explodedPath = explode("/", $path);

        $originalRoute = "";

        $match = true;
        foreach ($routes as $key => $route) {
            $explodedRoute = explode("/", $route->route);
            $originalRoute = $route->route;

            if (count($explodedRoute) !== count($explodedPath)) {
                $match = false;
                continue;
            } else {
                $match = true;
            }

            foreach ($explodedRoute as $routeKey => $routeValue) {
                if (str_starts_with($routeValue, "{") && str_ends_with($routeValue, "}")) continue;
                if ($routeValue !== $explodedPath[$routeKey]) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                break;
            }
        }


        if(!$match)
            return;

        $this->page = $this->database->table("quil_page_route")->
        select([
            "quil_page.id",
            "quil_page.name",
            "quil_page.description",
            "quil_page_content.content",
            "quil_page_route.route"
        ])->
        where(["route" => $originalRoute])->
        join("quil_page", "quil_page.route_id=quil_page_route.id")->
        join("quil_page_content", "quil_page.id=quil_page_content.page_id")->get()[0];
    }

    public function index(array|string $params): View
    {
        return $this->view($params);
    }

    protected function getViewContent(): string
    {
        $page = parent::getViewContent();

        return str_replace("{{ page.content }}", $this->page["content"], $page);
    }


    private function setTitle(string $title): void
    {
        $this->page["name"] = WEB_NAME . " | " . $title;
    }


    public function customPage(array|string $params): View
    {
        $this->getPage();

        if(empty($this->page)) {
            die("Not found");
        }

        $sources = new SourceManager($this->page["id"]);
        $sources->fetchSources();

        foreach ($sources->getSources() as $source) {
            if(!isset($params[$source->bind_param_url]) || $params[$source->bind_param_url] == null) {
                $value = $source->default_param_value;
            } else {
                $value = urldecode($params[$source->bind_param_url]);
            }

            $bundle[$source->source_name] = new DummySourceModel($value, $source->table_name, $source->column_selector);
        }

        $this->setTitle($this->page["name"]);

        $bundle["page"] = $this->page;

        return $this->view($bundle);
    }
}