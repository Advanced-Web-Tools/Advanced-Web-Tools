<?php

use Dashboard\classes\dashboard\DashboardPage;
use PackageManager\classes\PackageManager\PackagePackageManager;
use packages\manager\PackageManager;
use packages\installer\PackageInstaller;
use redirect\Redirect;
use view\View;

final class PackageManagerController extends DashboardPage
{

    private Redirect $redirect;

    public function __construct()
    {
        parent::__construct();
        $this->redirect = new Redirect();
    }

    public function index(array|string $params): View
    {

        $this->adminCheck();

        if (!$this->admin->checkPermission(1)) {
            $this->redirect->back();

            header("Location: {$this->redirect->redirectTo}");
            exit();
        }

        $filter = $params['filter'] ?? null;

        $packages = new PackagePackageManager();

        if ($filter) {
            $this->setTitle("Package Manager > " . ucfirst($filter) . " Packages");
        } else {
            $this->setTitle("Package Manager");
        }
        $result = [0 => null];
        if ($filter === 'plugins') {
            $result = $packages->getPlugins();
        } elseif ($filter === "themes") {
            $result = $packages->getThemes();
        } elseif ($filter === "system") {
            $result = $packages->getSystem();
        } else {
            $result = $packages->getPackages();
        }

        if (empty($result)) {
            $result = null;
        }

        $this->eventDispatcher->dispatch($this->event);
        return $this->view($this->getViewBundle(["packages" => $result, "filter" => ucfirst($filter)]));
    }

    public function disableAction(array|string $params): Redirect
    {
        $this->adminCheck();

        if (!$this->admin->checkPermission(1)) {
            return $this->redirect->back();
        }

        if (!isset($params["id"])) {
            $this->redirect->back();
            return $this->redirect;
        }

        if (!$this->admin->checkPermission(0)) {
            $this->redirect->back();
            return $this->redirect;
        }

        $packages = new PackagePackageManager();
        $packages->disablePackage($params["id"]);
        $this->redirect->back();
        return $this->redirect;
    }

    public function enableAction(array|string $params): Redirect
    {
        $this->adminCheck();
        if (!$this->admin->checkPermission(1)) {
            return $this->redirect->back();
        }

        if (!isset($params["id"])) {
            $this->redirect->back();
            return $this->redirect;
        }

        if (!$this->admin->checkPermission(0)) {
            $this->redirect->back();
            return $this->redirect;
        }

        $packages = new PackagePackageManager();
        $packages->enablePackage($params["id"]);
        $this->redirect->back();
        return $this->redirect;
    }

    /**
     * @throws ErrorException
     * @throws Exception
     */
    public function installPackage(array|string $params): Redirect
    {


        $this->adminCheck();

        if (!$this->admin->checkPermission(0)) {
            return $this->redirect->back();
        }


        $this->redirect->back();

        $installer = new PackageInstaller($_FILES["package"]);

        $installer->
        setDataOwner($this->packageName)->
        uploadPackage()->
        extractPackage()->
        installPackage()->
        transferPackageFiles()->
        extractData()->
        cleanUp();

        return $this->redirect;
    }

    public function uninstallPackage(array|string $params): Redirect
    {
        $this->adminCheck();

        if (!$this->admin->checkPermission(1)) {
            return $this->redirect->back();
        }

        $this->redirect->back();

        if ($this->admin->checkPermission(0)) {
            $uninstaller = new PackageManager();
            $uninstaller->removePackage($params["id"]);
        } else {
            $this->redirect->redirect("/dashboard/package_manager/error_permission");
        }


        return $this->redirect;
    }

}