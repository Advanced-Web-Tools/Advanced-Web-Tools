<?php

use admin\Admin;
use admin\model\AdminModel;
use AWTRespond\src\AWTRespond;
use AWTRespond\src\enums\EAWTRespondType;
use controller\Controller;
use database\DatabaseManager;
use Quil\classes\page\models\PageInfo;
use Quil\classes\page\models\PageRoute;
use Quil\classes\page\PageManager;
use Quil\classes\sources\SourceManager;
use redirect\Redirect;

final class QuilActionController extends Controller
{
    private PageManager $pageManager;
    private Admin $admin;
    private Redirect $redirect;
    private AWTRespond $responder;
    private DatabaseManager $database;

    public function __construct()
    {
        $this->redirect = new Redirect();
        $this->responder = new AWTRespond();
        $this->database = new DatabaseManager();
    }


    public function index(array|string $params): Redirect
    {
        // TODO: Implement index() method.
        return $this->redirect->back();
    }

    public function create(array|string $params): Redirect
    {
        $this->admin = $this->shared["Dashboard"]["Admin"];
        $this->pageManager = new PageManager();

        if (!$this->admin->checkAuthentication() || !$this->admin->checkPermission(2)) {
            return $this->redirect->back();
        }

        $this->pageManager->createPage($_POST['page_name']);

        $this->redirect->redirect("/dashboard/pages/success");
        return $this->redirect;

    }

    public function delete(array|string $params): Redirect
    {
        $this->admin = new Admin();
        $this->pageManager = new PageManager();

        if (!$this->admin->checkAuthentication() || !$this->admin->checkPermission(2)) {
            return $this->redirect->back();
        }

        $this->pageManager->deletePage($params['id']);
        return $this->redirect->back();
    }

    public function save(array|string $params): AWTRespond
    {
        $this->admin = new Admin();
        $this->pageManager = new PageManager();

        if (!$this->admin->checkAuthentication() || !$this->admin->checkPermission(2)) {
            return $this->responder->back();
        }

        $rawBody = file_get_contents('php://input'); // Raw JSON body
        $decodedBody = json_decode($rawBody, true);
        $res = $this->pageManager->savePage($decodedBody);
        if ($res) {
            return $this->responder->setCode(200)->setType(EAWTRespondType::JSON)->setContent(["code" => 200, "content" => "Page was saved!"]);
        } else {
            return $this->responder->setCode(500)->setType(EAWTRespondType::JSON)->setContent(["code" => 500, "content" => "Something went wrong while saving the page! <br> Please try again later."]);
        }
    }

    public function createRoute(array|string $params): Redirect
    {
        $this->admin = $this->shared["Dashboard"]["Admin"];;
        $this->pageManager = new PageManager();

        if (!$this->admin->checkAuthentication() || !$this->admin->checkPermission(2)) {
            return $this->redirect->back();
        }

        $this->pageManager->createRoute($_POST["route_path"]);

        return $this->redirect->back();
    }


    public function deleteRoute(array|string $params): Redirect
    {
        $this->admin = $this->shared["Dashboard"]["Admin"];;
        $this->pageManager = new PageManager();

        if (!$this->admin->checkAuthentication() || !$this->admin->checkPermission(2)) {
            return $this->redirect->back();
        }

        $this->pageManager->deleteRoute($params['id']);

        return $this->redirect->back();
    }

    public function getInfo(array|string $params): AWTRespond
    {
        $this->admin = $this->shared["Dashboard"]["Admin"];;
        $this->pageManager = new PageManager();

        if (!$this->admin->checkAuthentication() || !$this->admin->checkPermission(2)) {
            return $this->responder->back();
        }

        $page = new PageInfo($params['id']);

        if ($page->route_id !== null)
            $route = new PageRoute($page->route_id);
        if ($page->admin !== null)
            $admin = new AdminModel($page->created_by);

        $info = [
            "page" => [
                "id" => $page->id,
                "name" => $page->name,
                "description" => $page->description,
                "creation_date" => $page->creation_date
            ],
            "route" => [
                "path" => $route->route,
                "id" => $page->route_id,
            ],
            "author" => [
                "name" => $admin->username
            ],
            "routes" => [
                $this->pageManager->fetchRoutes()->returnRoutes()
            ]
        ];

        return $this->responder->setCode(200)->setType(EAWTRespondType::JSON)->setContent(["code" => 200, "content" => json_encode($info)]);
    }

    public function getSources(array|string $params): AWTRespond
    {

        $srcManager = new SourceManager($params["id"]);

        $tables = $srcManager->getTables();
        $sources = $srcManager->fetchSources()->getSources();


        return $this->responder->setCode(200)->setType(EAWTRespondType::JSON)->setContent(["code" => 200, "sources" => $sources, "tables" => $tables]);
    }

    public function addSource(array|string $params): AWTRespond
    {
        $this->admin = $this->shared["Dashboard"]["Admin"];;
        $this->pageManager = new PageManager();

        if (!$this->admin->checkPermission(1)) {
            return $this->responder->setCode(403)->setType(EAWTRespondType::JSON)->setContent(["code" => 403]);
        }

        $rawBody = file_get_contents('php://input'); // Raw JSON body
        $post = json_decode($rawBody, true);

        $srcManager = new SourceManager($params["id"]);
        $res = $srcManager->addSource($post['table_id'], $post['column'], $post['url_param'], $post['defaultValue'], $post['name']);

        if ($res == null) {
            return $this->responder->setCode(500)->setType(EAWTRespondType::JSON)->setContent(["code" => 500]);
        } else {
            return $this->responder->setCode(200)->setType(EAWTRespondType::JSON)->setContent(["code" => 200]);
        }
    }

    public function updateSource(array|string $params): AWTRespond
    {
        $this->admin = $this->shared["Dashboard"]["Admin"];;
        $this->pageManager = new PageManager();

        if (!$this->admin->checkPermission(1)) {
            return $this->responder->setCode(403)->setType(EAWTRespondType::JSON)->setContent(["code" => 403]);
        }

        $rawBody = file_get_contents('php://input'); // Raw JSON body
        $post = json_decode($rawBody, true);


        $srcManager = new SourceManager($params["id"]);
        $res = $srcManager->updateSource($post['id'], $post['table_id'], $post['column'], $post['url_param'], $post['defaultValue'], $post['name']);

        if (!$res) {
            return $this->responder->setCode(500)->setType(EAWTRespondType::JSON)->setContent(["code" => 500]);
        } else {
            return $this->responder->setCode(200)->setType(EAWTRespondType::JSON)->setContent(["code" => 200]);
        }
    }

    public function delSource(array|string $params): AWTRespond
    {
        $this->admin = $this->shared["Dashboard"]["Admin"];
        $this->pageManager = new PageManager();

        if (!$this->admin->checkPermission(1)) {
            return $this->responder->setCode(403)->setType(EAWTRespondType::JSON)->setContent(["code" => 403]);
        }

        $rawBody = file_get_contents('php://input');
        $post = json_decode($rawBody, true);

        $srcManager = new SourceManager($params["id"]);
        $res = $srcManager->deleteSource($post['id']);

        if ($res == null) {
            return $this->responder->setCode(500)->setType(EAWTRespondType::JSON)->setContent(["code" => 500]);
        } else {
            return $this->responder->setCode(200)->setType(EAWTRespondType::JSON)->setContent(["code" => 200]);
        }
    }

}