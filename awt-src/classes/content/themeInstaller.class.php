<?php

namespace content;

use ZipArchive;
use XMLReader;
use database\databaseConfig;
use admin\profiler;
use notifications\notifications;
use Exception;

class themeInstaller extends fileScanner
{

    private object $database;
    private object $mysqli;
    private object $xml;

    public function __construct()
    {
        $this->database = new databaseConfig;

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");
        $this->mysqli = $this->database->getConfig();

        $this->xml = new XMLReader;
    }

    public function packageExtractor()
    {
        $target_dir = TEMP;
        $file_cp = $_FILES["file"]["name"];
        $fileType = strtolower(pathinfo($file_cp, PATHINFO_EXTENSION));
        $target_file = $target_dir . substr(hash("SHA512", basename($_FILES["file"]["name"]) . time()), 0, 10) . $fileType;

        if ($fileType !== 'zip') {
            $return['installer']['notice']['Fatal'] = 'Only zip files allowed! ' . $fileType . ' was provided!';
            $return['installer']['info']['error'] = 'Error parsing the package';
            return $return;
        }

        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        $zip = new ZipArchive;
        $folderName = substr(hash('SHA512', $_FILES['file']['name']), 0, 10);
        $folder = TEMP . $folderName;
        if (!file_exists($folder))
            mkdir($folder);
        $zip->open($target_file);
        $zip->extractTo($folder);
        $zip->close();

        unlink($target_file);

        $return['installer']['info'] = array();

        if (!file_exists($folder . DIRECTORY_SEPARATOR . 'theme.php')) {
            $return['installer']['notice']['Fatal'] = "Missing theme.php";
            $return['installer']['info']['error'] = 'Error parsing the package';
            $this->rrmdir($folder);
            return $return;
        }

        if (!file_exists($folder . DIRECTORY_SEPARATOR . 'theme.xml')) {
            $return['installer']['notice']['Fatal'] = "Missing theme.xml";
            $return['installer']['info']['error'] = 'Error parsing the package';
            $this->rrmdir($folder);
            return $return;
        }


        $this->xml->open($folder . DIRECTORY_SEPARATOR . 'theme.xml');

        while ($this->xml->read()) {

            if ($this->xml->nodeType == XMLReader::END_ELEMENT) {
                continue;
            }

            if ($this->xml->name != 'theme' && $this->xml->name != '#text') {
                if ($this->xml->name == 'name')
                    $return['installer']['info']["name"] = $this->xml->readString();
                if ($this->xml->name == 'description')
                    $return['installer']['info']["description"] = $this->xml->readString();
                if ($this->xml->name == 'placeholder')
                    $return['installer']['info']["placeholder"] = $this->xml->readString();
                if ($this->xml->name == 'version')
                    $return['installer']['info']["version"] = $this->xml->readString();
            }
        }

        $this->xml->close();

        $files = $this->searchDirectory($folder);

        $return['installer']['id'] = $folderName;

        foreach ($files as $file) {
            $check = $this->checkForForbidden($file);
            if ($check !== true) {
                $return['installer']['notice']['IMPORTANT'] = $check;
                return $return;
            }
        }

        $return['installer']['notice']['Ok'] = "All is set!";
        return $return;
    }

    private function moveDirectory($src, $dest)
    {

        if (!is_dir($src)) {
            return false;
        }

        if (!is_dir($dest)) {
            mkdir($dest, 0777, true);
        }


        $dir = opendir($src);
        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $srcFile = $src . '/' . $file;
                $destFile = $dest . '/' . $file;

                if (is_dir($srcFile)) {
                    if (!$this->moveDirectory($srcFile, $destFile)) {
                        closedir($dir);
                        return false;
                    }
                } else {
                    if (!rename($srcFile, $destFile)) {
                        closedir($dir);
                        return false;
                    }
                }
            }
        }

        // Close the source directory and delete it
        closedir($dir);
        rmdir($src);

        return true;
    }

    public function installerAction($action, $path, $name)
    {
        if (!is_dir(TEMP . $path))
            return "Unexpected error has occured. Please try again later.";

        if ($action == 'cancel') {
            $this->rrmdir(TEMP . $path);
            return "Installation was canceled. You can reload now or wait 5 seconds.";
        }

        $placeholder = null;

        if ($action == 'install' && $name != '') {
            $this->xml = new XMLReader;
            $this->xml->open(TEMP . $path . DIRECTORY_SEPARATOR . 'theme.xml');

            while ($this->xml->read()) {
                if ($this->xml->nodeType == XMLReader::END_ELEMENT) {
                    continue;
                }
                if ($this->xml->name != 'theme' && $this->xml->name != '#text') {
                    if ($this->xml->name == 'name')
                        $name = $this->xml->readString();
                    if ($this->xml->name == 'version')
                        $version = $this->xml->readString();
                    if ($this->xml->name == 'description')
                        $description = $this->xml->readString();
                    if ($this->xml->name == 'placeholder')
                        $placeholder = $this->xml->readString();
                }
            }

            $this->xml->close();
            $status = 0;
            $stmt = $this->mysqli->prepare("INSERT INTO `awt_themes` (`name`, `description`, `version`, `placeholder`, `active`) VALUES (?, ?, ?, ?, ?);");
            $stmt->bind_param("ssssi", $name, $description, $version, $placeholder, $status);
            $stmt->execute();
            $this->moveDirectory(TEMP . $path, THEMES . $name);

            $profiler = new profiler;
            $notifications = new notifications("Installer", $profiler->name . " has installed new theme: $name", "important");
            $notifications->pushNotification();

            return $name . " was installed succesfully! You can reload now or wait 5 seconds.";
        }

        return "Invalid operation";
    }


    public function removeTheme($name, $id)
    {
        $this->rrmdir(THEMES . $name);
        unlink(THEMES . $name);
        $stmt = $this->mysqli->prepare("DELETE FROM `awt_themes` WHERE `id` = ? AND `name` = ?;");
        $stmt->bind_param("is", $id, $name);
        $stmt->execute();
        $stmt->close();

        $profiler = new profiler;
        $notifications = new notifications("Installer", $profiler->name . " has removed theme: $name", "important");
        $notifications->pushNotification();

        return true;
    }

    public function installFromStore(string $path)
    {

        try {

            $zip = new ZipArchive;

            $zip->open($path);

            $new_path = TEMP . substr(hash('SHA512', $path), 0, 10);

            mkdir($new_path);

            $zip->extractTo($new_path);

            $zip->close();

            $this->xml = new XMLReader;
            $this->xml->open($new_path . DIRECTORY_SEPARATOR . 'theme.xml');

            while ($this->xml->read()) {
                if ($this->xml->nodeType == XMLReader::END_ELEMENT) {
                    continue;
                }
                if ($this->xml->name != 'theme' && $this->xml->name != '#text') {
                    if ($this->xml->name == 'name')
                        $name = $this->xml->readString();
                    if ($this->xml->name == 'version')
                        $version = $this->xml->readString();
                    if ($this->xml->name == 'description')
                        $description = $this->xml->readString();
                    if ($this->xml->name == 'placeholder')
                        $placeholder = $this->xml->readString();
                }
            }

            $this->xml->close();
            $status = 0;
            $stmt = $this->mysqli->prepare("INSERT INTO `awt_themes` (`name`, `description`, `version`, `placeholder`, `active`) VALUES (?, ?, ?, ?, ?);");
            $stmt->bind_param("ssssi", $name, $description, $version, $placeholder, $status);
            $stmt->execute();
            $this->moveDirectory($new_path, THEMES . $name);

            $profiler = new profiler;
            $notifications = new notifications("Installer", $profiler->name . " has installed new theme: $name", "important");
            $notifications->pushNotification();

            return true;


        } catch (Exception $e) {
            return false;
        }
    }

}