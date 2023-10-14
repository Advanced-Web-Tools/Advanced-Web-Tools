<?php

namespace media;


use api\api;
use database\databaseConfig;
use Exception;
use media\albums;

class media extends api
{

    private object $database;
    private object $mysqli;
    private object $albums;

    public function __construct()
    {
        parent::__construct();

        $this->database = new databaseConfig();

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");
        $this->mysqli = $this->database->getConfig();

        $this->albums = new albums();
    }

    public function uploadFiles(array $uploadedFiles)
    {
        $uploadedFileNames = [];

        $validImageFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $validVideoFormats = ['mp4', 'avi', 'mov', 'wmv', 'flv'];
        $validAudioFormats = ['mp3', 'wav', 'ogg', 'aac', 'flac'];

        foreach ($uploadedFiles['name'] as $index => $fileName) {
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (in_array($extension, $validImageFormats)) {
                $fileType = 'image';
            } elseif (in_array($extension, $validVideoFormats)) {
                $fileType = 'video';
            } elseif (in_array($extension, $validAudioFormats)) {
                $fileType = 'audio';
            } else {
                continue;
            }

            $uniqueName = hash('sha512', $fileName . time());
            $uniqueName = substr($uniqueName, 0, 15);
            $newFileName = $uniqueName . '.' . $extension;

            $destinationFolder = UPLOADS . $fileType . '/';
            if (!file_exists($destinationFolder)) {
                mkdir($destinationFolder, 0755, true);
            }

            if (move_uploaded_file($uploadedFiles['tmp_name'][$index], $destinationFolder . $newFileName)) {

                $uploadedFileNames[] = $newFileName;
                $this->addToDatabase($newFileName, $fileType, $fileName);

            } else {
                throw new \Exception('Failed to upload ' . $fileName);
            }
        }

        return $uploadedFileNames;
    }

    private function addToDatabase(string $file, string $type, string $original)
    {

        $file = HOSTNAME . "awt-data/uploads/" . $type . "/" . $file;

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_media`(`file`, `name`, `file_type`) VALUES (?, ?, ?);");
        $stmt->bind_param("sss", $file, $original, $type);
        $stmt->execute();
        $stmt->close();
    }

    private function isValidImage($filePath)
    {
        $imageInfo = getimagesize($filePath);
        return $imageInfo !== false && in_array($imageInfo['mime'], ['image/jpeg', 'image/png', 'image/gif']);
    }

    public function getMedia()
    {
        $stmt = $this->mysqli->prepare("SELECT `id`, `file_type`, `file` FROM `awt_media`;");
        $stmt->execute();
        $fetched = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $fetched;
    }

    public function deleteMedia(string $id)
    {
        $stmt = $this->mysqli->prepare("SELECT `file_type`, `file` FROM `awt_media` WHERE `id` = ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $fileType = $row["file_type"];
            $file = UPLOADS . $fileType . '/' . $row["file"];

            $stmt->close();
            $stmt = $this->mysqli->prepare("DELETE FROM `awt_media` WHERE `id` = ?;");
            $stmt->bind_param("s", $id);
            $stmt->execute();

            try {
                unlink($file);
                return true;
            } catch (Exception) {
                return false;
            }

        } else {
            $stmt->close();
            return false;
        }
    }

    public function moveToAlbum(string $mediaId, string $albumId)
    {
        $stmt = $this->mysqli->prepare("UPDATE `awt_media` SET `album_id` = ? WHERE `id` = ?;");
        $stmt->bind_param("ss", $albumId, $mediaId);
        $stmt->execute();
    }


    public function getMediaFromAlbum(string $albumId)
    {

        if ($albumId == "all") {
            $stmt = $this->mysqli->prepare("SELECT `id`, `file_type`, `file` FROM `awt_media`;");
        } else {
            $stmt = $this->mysqli->prepare("SELECT `id`, `file_type`, `file` FROM `awt_media` WHERE `album_id` = ?;");
            $stmt->bind_param("s", $albumId);
        }

        $stmt->execute();
        $fetched = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $fetched;
    }

    public function globallyRemoveFromAlbum(string $albumId)
    {
        $stmt = $this->mysqli->prepare("UPDATE `awt_media` SET `album_id` = NULL WHERE `album_id` = ?;");
        $stmt->bind_param("s", $albumId);
        $stmt->execute();
        $stmt->close();
    }

    public function Api()
    {
        parent::Api();

        if ($this->checkForData()) {

            $data = $_POST['data'];

            if (isset($_POST["type"]) && $_POST["type"] == "getFromAlbum") {
                $stmt = $this->mysqli->prepare("SELECT * FROM `awt_media` WHERE `album_id` = ?;");
                $stmt->bind_param("s", $data);
                $stmt->execute();
                $fetched = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();

                die(json_encode($fetched));
            }

            if (isset($_POST["type"]) && $_POST["type"] == "fetchAll") {
                $stmt = $this->mysqli->prepare("SELECT * FROM `awt_media` WHERE 1 = 1;");
                $stmt->execute();
                $fetched = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();

                die(json_encode($fetched));
            }

            $stmt = $this->mysqli->prepare("SELECT * FROM `awt_media` WHERE `id` = ?;");
            $stmt->bind_param("s", $data);
            $stmt->execute();
            $fetched = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            die(json_encode($fetched));
        }

        die("No data set!");

    }

}