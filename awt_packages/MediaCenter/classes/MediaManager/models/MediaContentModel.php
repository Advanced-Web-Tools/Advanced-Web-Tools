<?php

namespace MediaCenter\classes\MediaManager\models;

use data\models\DataModel;
use model\Model;

class MediaContentModel extends Model
{
    public ?int $media_id = null;

    public ?string $name = null;
    public ?int $data_id = null;

    public ?DataModel $data = null;

    public ?string $type;

    public function __construct(int $id, int $data_id, string $name, ?array $data = null) {
        parent::__construct();

        $this->data_id = $data_id;
        $this->name = $name;
        if($data != null) {
            $this->data = new DataModel($data_id);
        } else {
            $this->data = new DataModel();
            $this->data->buildFromArray($data);
            $this->data->setFileLocation("/" . $this->data->file_location);
        }
        $this->type = $this->data->dataType;
    }
}