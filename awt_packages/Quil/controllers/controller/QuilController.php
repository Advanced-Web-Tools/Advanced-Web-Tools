<?php


use Dashboard\classes\dashboard\DashboardPage;
use Quil\classes\editor\event\EQuilEditor;
use Quil\classes\page\PageManager;
use Quil\classes\page\models\PageContent;
use redirect\Redirect;
use view\View;

final class QuilController extends DashboardPage
{
    private PageManager $pageManager;

    public function __construct()
    {
        parent::__construct();
    }

    public function setPageManager(PageManager $pageManager): void
    {
        $this->pageManager = $pageManager;
    }


    public function index(array|string $params): View
    {
        $this->adminCheck();
        $this->setTitle("Pages and Routes");

        $this->eventDispatcher->dispatch($this->event);

        $pages = $this->pageManager->fetchPages()->returnPages();

        $routes = $this->pageManager->fetchRoutes()->returnRoutes();

        return $this->view($this->getViewBundle(["pages" => $pages, "custom_routes" => $routes]));
    }

    public function editor(array|string $params): View|Redirect
    {
        $redirect = new Redirect();
        if(!$this->admin->checkAuthentication() || !$this->admin->checkPermission(2)) {
            return $redirect->back();
        }

        if(!isset($params['id']))
        {

            return $redirect->back();
        }

        $page = new PageContent((int) $params["id"]);

        $this->setTitle($page->name);

        if($page->content === null || $page->content === "") {
            $page->content = "<div class='page'></div>";
        }

        $bundle["page"] = $page;

        $event = new EQuilEditor();

        $this->eventDispatcher->dispatch($event);

        $bundle["editor_scripts"] = $event->retrieveScripts();

        $this->eventDispatcher->dispatch($this->event);
        return $this->view($this->getViewBundle($bundle));
    }
}