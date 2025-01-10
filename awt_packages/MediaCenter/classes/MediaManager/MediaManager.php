<?php

namespace MediaCenter\classes\MediaManager;

use data\DataManager;
use data\models\DataModel;
use database\DatabaseManager;
use ErrorException;
use MediaCenter\classes\MediaManager\models\MediaContentModel;

final class MediaManager
{
    public array $contentByType;
    public array $objects;
    private DatabaseManager $database;

    public function __construct()
    {
        $this->database = new DatabaseManager();
        $this->objects = [];
        $this->contentByType = [];
    }

    public function fetchContent(): void
    {
        $data = $this->database->
        table("media_center_content")->
        select()
            ->join("awt_data", "awt_data.id = media_center_content.data_id")
            ->get();

        foreach ($data as $key => $value) {
            $model = new MediaContentModel($value['media_id'], $value['data_id'], $value['name'], $value);
            $this->objects[] = $model;
            $this->contentByType[$model->data->dataType][] = $model;
        }
    }

    public function getOfType(string $dataType): array
    {

        return $this->contentByType[$dataType] ?? [];
    }

    /**
     * @throws ErrorException
     */
    public function uploadFile(array $files): bool
    {
        $dataManager = new DataManager();

        $fileCount = count($files['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $file = [
                "name" => $files['name'][$i],
                "type" => $files['type'][$i],
                "tmp_name" => $files['tmp_name'][$i],
                "error" => $files['error'][$i],
                "size" => $files['size'][$i],
            ];

            $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            $type = match ($extension) {
                'jpg', 'jpeg', 'png', 'gif', 'webp' => "image",
                'mp3', 'wav', 'ogg' => "audio",
                'mp4', 'avi', 'mov', 'webm' => "video",
                'pdf', 'doc', 'docx', 'xls', 'xlsx' => "document",
                default => "other",
            };



            $data = $dataManager->uploadData($file, $file["name"], $type, "User");

            if (!$data instanceof DataModel) {
                return false;
            }

            $result = $this->database->table("media_center_content")->insert([
                "data_id" => $data->id,
                "name" => $file["name"],
            ])->executeInsert();

            if ($result === null) {
                return false;
            }
        }

        return true;
    }



}