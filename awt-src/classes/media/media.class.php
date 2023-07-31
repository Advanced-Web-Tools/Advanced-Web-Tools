<?php 

namespace media;


use database\databaseConfig;
use media\albums;

class media {

    private object $database;
    private object $mysqli;
    private object $albums;

    public function __construct()
    {
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
            $newFileName = $uniqueName . '.' . $extension;
    
            $destinationFolder = UPLOADS . $fileType . '/';
            if (!file_exists($destinationFolder)) {
                mkdir($destinationFolder, 0755, true);
            }
    
            if (move_uploaded_file($uploadedFiles['tmp_name'][$index], $destinationFolder . $newFileName)) {

                $uploadedFileNames[] = $newFileName;
                $this->addToDatabase($newFileName, $fileType);

            } else {
                throw new \Exception('Failed to upload ' . $fileName);
            }
        }
    
        return $uploadedFileNames;
    }
    
    private function addToDatabase(string $file, string $type, ) {
        $stmt = $this->mysqli->prepare("INSERT INTO `awt_media`(`file`, `file_type`) VALUES (?, ?);");
        $stmt->bind_param("ss", $file, $type);
        $stmt->execute();
        $stmt->close();
    }

    private function isValidImage($filePath)
    {
        $imageInfo = getimagesize($filePath);
        return $imageInfo !== false && in_array($imageInfo['mime'], ['image/jpeg', 'image/png', 'image/gif']);
    }
    
    

}