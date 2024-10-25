<?php
use packages\runtime\api\RuntimeControllerAPI;
use packages\runtime\api\RuntimeRouterAPI;
use router\Router;

final class MediaRoutes extends RuntimeRouterAPI
{
    private RuntimeControllerAPI $controller;


    public function setup(): void
    {
        $this->controller = $this->getPassable("MediaCenter", "MediaCenterController");

    }

    public function main(): void
    {
        $this->addRouter(new Router("/dashboard/media", "index", $this->controller->getController("MediaCenter")));
        $this->addRouter(new Router("/dashboard/media/{filter}", "index", $this->controller->getController("MediaCenter")));

        //Actions
        $this->addRouter(new Router("/dashboard/mediacenter/actions/delete/{id}", "delete", $this->controller->getController("MediaAction")));
        $this->addRouter(new Router("/api/mediacenter/getMedia/", "fetchAllMedia", $this->controller->getController("MediaAction")));
    }
}