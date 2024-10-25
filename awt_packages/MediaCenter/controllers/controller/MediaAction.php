<?php

use AWTRespond\src\AWTRespond;
use AWTRespond\src\enums\EAWTRespondType;
use controller\Controller;
use data\DataManager;
use MediaCenter\classes\MediaManager\MediaManager;
use view\View;
use redirect\Redirect;

final class MediaAction extends Controller
{

    public function index(array|string $params): View|Redirect
    {
        $red = new Redirect();
        return $red->back();
    }

    public function fetchAllMedia(array|string $params): Redirect {

        $manager = new MediaManager();

        $response = new AWTRespond();

        $response->setType(EAWTRespondType::JSON);
        $response->setContent($manager->content);
        $response->setCode(200);
        return $response;
    }

    public function delete(array|string $params): Redirect
    {
        $red = new Redirect();
        $manager = new DataManager();
        $manager->deleteData($params["id"]);
        return $red->back();
    }
}