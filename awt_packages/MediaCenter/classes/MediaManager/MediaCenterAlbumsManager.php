<?php

namespace MediaCenter\classes\MediaManager;

use MediaCenter\classes\MediaManager\models\MediaCenterAlbum;
use model\Model;

final class MediaCenterAlbumsManager extends Model
{
    public array $albums = [];

    public function __construct()
    {
        parent::__construct();

        $result = $this->selectAll("media_center_album");

        foreach ($result as $row) {
            $this->albums[] = new MediaCenterAlbum($row["id"]);
        }
    }

    public function __toString() : string {
        $return = [];
        foreach ($this->albums as $album) {
            $return[] = $album->__toArray();
        }

        return json_encode($this->__toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

//    public function __toArray(): array
//    {
//        $return = parent::__toArray();
//
////        foreach ($this->albums as $album) {
////            $return[] = $album->__toArray();
////        }
////
//        return $return;
//    }

}