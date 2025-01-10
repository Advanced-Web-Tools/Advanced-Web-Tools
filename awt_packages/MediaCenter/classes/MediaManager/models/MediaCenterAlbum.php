<?php

namespace MediaCenter\classes\MediaManager\models;

use ErrorException;
use model\Model;

class MediaCenterAlbum extends Model
{
    public int $id;
    public string $name;
    public array $albumContent;

    /**
     * @throws ErrorException
     */
    public function __construct(int $id)
    {
        parent::__construct();
        $this->id = $id;
        $this->selectByID($id, "media_center_album");
        $this->__destruct();
        $content = $this->table("media_center_album_content")->select()->where(["album_id" => $this->id])->get();

        foreach ($content as $key => $value) {
            $this->albumContent[] = new MediaContentModel($value['content_id']);
        }
    }
}