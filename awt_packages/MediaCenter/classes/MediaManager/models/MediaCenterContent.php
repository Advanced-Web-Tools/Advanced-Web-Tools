<?php

namespace MediaCenter\classes\MediaManager\models;

use data\Data;
use data\DataManager;
use database\DatabaseManager;
use ErrorException;
use model\Model;

class MediaCenterContent extends Model
{
    public int $id;
    public int $data_id;

    public string $name;
    public Data $data;
    private DataManager $manager;

    /**
     * @throws ErrorException
     */
    public function __construct(int $id) {
        parent::__construct();
        $this->selectByID($id, "media_center_content");
        $this->data_id = $this->getParam("data_id");

        $this->manager = new DataManager();
        $this->data = $this->manager->fetchData($this->data_id)->getData($this->data_id);
        $this->data->getLocation();
    }
}