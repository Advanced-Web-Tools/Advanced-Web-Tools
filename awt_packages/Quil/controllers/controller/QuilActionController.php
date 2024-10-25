<?php
use admin\Admin;
use controller\Controller;
use JetBrains\PhpStorm\NoReturn;
use Quil\classes\page\PageManager;
use redirect\Redirect;
final class QuilActionController extends Controller
{
    private PageManager $pageManager;
    private Admin $admin;
    private Redirect $redirect;

    public function __construct()
    {
        $this->admin = new Admin();
        $this->pageManager = new PageManager();
        $this->redirect = new Redirect();
    }


    public function index(array|string $params): Redirect
    {
        // TODO: Implement index() method.
        return $this->redirect->back();
    }

    #[NoReturn] public function create(array|string $params): Redirect
    {
        if (!$this->admin->checkPermission(1)) {
            return $this->redirect->back();
        }

        $this->pageManager->createPage($_POST['page_name']);

        $this->redirect->redirect("/dashboard/pages/success");
        return $this->redirect;

    }

    public function delete(array|string $params): Redirect
    {
        if (!$this->admin->checkPermission(1)) {
            $this->redirect->back();
        }

        $this->pageManager->deletePage($params['id']);
        return $this->redirect->back();
    }
}