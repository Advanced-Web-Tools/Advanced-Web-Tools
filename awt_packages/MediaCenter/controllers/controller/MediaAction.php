<?php

use admin\Admin;
use AWTRespond\src\AWTRespond;
use AWTRespond\src\enums\EAWTRespondType;
use controller\Controller;
use data\DataManager;
use MediaCenter\classes\MediaManager\MediaManager;
use redirect\Redirect;
use view\View;

final class MediaAction extends Controller
{

    private Admin $admin;

    public function index(array|string $params): View|Redirect
    {
        $red = new Redirect();
        return $red->back();
    }

    public function fetchAllMedia(array|string $params): Redirect
    {

        $manager = new MediaManager();

        $response = new AWTRespond();

        $manager->fetchContent();

        $response->setType(EAWTRespondType::JSON);
        $response->setContent($manager->objects);
        $response->setCode(200);
        return $response;
    }

    public function upload(array|string $params): Redirect
    {

        $this->admin = $this->shared["Dashboard"]["Admin"];
        $red = new Redirect();

        if (!$this->admin->checkPermission(2) || !$this->admin->checkAuthentication()) {
            return $red->back();
        }

        $manager = new MediaManager();
        if ($_FILES['upload']) {
            foreach ($_FILES['upload']['error'] as $index => $error) {
                if ($error !== UPLOAD_ERR_OK)
                    return $red->back();
            }
        }
        $manager->uploadFile($_FILES['upload']);

        return $red->back();
    }

    /**
     * @throws ErrorException
     */
    public function delete(array|string $params): Redirect
    {
        $this->admin = new Admin();
        $red = new Redirect();

        if (!$this->admin->checkPermission(2) || !$this->admin->checkAuthentication()) {
            return $red->back();
        }

        $manager = new DataManager();
        $manager->fetchData($params["id"]);
        $manager->deleteData($params["id"]);
        return $red->back();
    }
}