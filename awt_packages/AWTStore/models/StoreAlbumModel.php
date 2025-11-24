<?php
use model\Model;

class StoreAlbumModel extends Model {
    public array $images;

    public function __construct(array $images)
    {
        parent::__construct();

        $this->images = $images;
    }
}