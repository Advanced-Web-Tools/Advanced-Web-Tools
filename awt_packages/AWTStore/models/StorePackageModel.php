<?php
use model\Model;

class StorePackageModel extends Model
{
    public string $storeId;
    public string $name;
    public string $description;
    public string $iconURL;
    public string $downloadURL;
    public StoreAlbumModel $album;

    public function __construct(array $json)
    {
        parent::__construct();

        $this->storeId = $json["storeId"];
        $this->name = $json["name"];
        $this->description = $json["description"];
        $this->iconURL = $json["iconURL"];
        $this->downloadURL = $json["downloadURL"];

        $this->album = new StoreAlbumModel($json["album"]);

    }
}