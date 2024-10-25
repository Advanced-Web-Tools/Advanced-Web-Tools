<?php

namespace MediaCenter\classes\MediaManager;

use data\enums\EDataType;
use ErrorException;
use MediaCenter\classes\MediaManager\models\MediaCenterContent;
use model\Model;

final class MediaManager extends Model
{
    public array $content;
    public array $objects;

    /**
     * @throws ErrorException
     */
    public function __construct()
    {
        parent::__construct();
        $this->content = [];
        $this->objects = [];
        $result = $this->selectAll("media_center_content");

        foreach ($result as $row) {
            $content_object = new MediaCenterContent($row["id"]);
            $this->objects[$row["id"]] = $content_object;
            $this->content[$row["id"]] = $content_object->__toArray();
        }
    }

    public function getOfType(EDataType $dataType): array
    {
        $result = [];
        foreach ($this->objects as $key => $object) {
            if ($object->data->getDataType() === $dataType) {
                $result[$key] = $this->content[$key];
            }
        }

        return $result;
    }

}