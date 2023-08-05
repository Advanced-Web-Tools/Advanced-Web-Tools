<?php

namespace content;
use ZipArchive;
use XMLReader;
use database\databaseConfig;
use notifications\notifications;
use admin\profiler;

class pluginInstaller extends fileScanner{

    private object $database;
    private object $mysqli;
    private object $xml;

    public function __construct() {
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
        if (!file_exists($folder)) mkdir($folder);
        $zip->open($target_file);
        $zip->extractTo($folder);
        $zip->close();

        unlink($target_file);

        if (!file_exists($folder . DIRECTORY_SEPARATOR . 'plugin.main.php')) {
            $return['installer']['notice']['Fatal'] = "Missing plugin.main.php";
            $return['installer']['info']['error'] = 'Error parsing the package';
            $this->rrmdir($folder);
            return $return;
        }

        if (!file_exists($folder . DIRECTORY_SEPARATOR . 'plugin.xml')) {
            $return['installer']['notice']['Fatal'] = "Missing plugin.xml";
            $return['installer']['info']['error'] = 'Error parsing the package';
            $this->rrmdir($folder);
            return $return;
        }

        $this->xml->open($folder . DIRECTORY_SEPARATOR . 'plugin.xml');

        while ($this->xml->read()) {
            if ($this->xml->nodeType == XMLReader::END_ELEMENT) {
                continue;
            }
            if ($this->xml->name != 'plugin' && $this->xml->name != '#text' && $this->xml->name != 'authorizationFile') {
                if ($this->xml->name == 'requiresAuthorization') {
                    $return['installer']['info']["Database access required"] = $this->xml->readString();
                } else {
                    $return['installer']['info'][$this->xml->name] = $this->xml->readString();
                }
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
        // Check if the source directory exists
        if (!is_dir($src)) {
            return false;
        }

        // Create the destination directory if it doesn't exist
        if (!is_dir($dest)) {
            mkdir($dest, 0777, true);
        }

        // Open the source directory and loop through its contents
        $dir = opendir($src);
        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $srcFile = $src . '/' . $file;
                $destFile = $dest . '/' . $file;

                if (is_dir($srcFile)) {
                    // Recursively move subdirectory
                    if (!$this->moveDirectory($srcFile, $destFile)) {
                        closedir($dir);
                        return false;
                    }
                } else {
                    // Move file
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
        if(!is_dir(TEMP . $path)) return "Unexpected error has occured. Please try again later.";
        
        if ($action == 'cancel') {
            $this->rrmdir(TEMP . $path);
            return "Installation was canceled. You can reload now or wait 5 seconds.";
        }

        if ($action == 'install' && $name != '') {
            $this->xml = new XMLReader;
            $this->xml->open(TEMP . $path . DIRECTORY_SEPARATOR . 'plugin.xml');

            while ($this->xml->read()) {
                if ($this->xml->nodeType == XMLReader::END_ELEMENT) {
                    continue;
                }
                if ($this->xml->name != 'plugin' && $this->xml->name != '#text') {
                    if ($this->xml->name == 'name') $name = $this->xml->readString();
                    if ($this->xml->name == 'icon') $icon = $this->xml->readString();
                    if ($this->xml->name == 'version') $version = $this->xml->readString();
                    if ($this->xml->name == 'description') $description = $this->xml->readString();
                }
            }

            $this->xml->close();
            $status = 0;
            $stmt = $this->mysqli->prepare("INSERT INTO `awt_plugins` (`name`, `icon`, `version`, `description`, `status`) VALUES (?, ?, ?, ?, ?);");
            $stmt->bind_param("ssssi", $name, $icon, $version, $description, $status);
            $stmt->execute();
            $this->moveDirectory(TEMP . $path, PLUGINS . $name);

            $profiler = new profiler;
            $notifications = new notifications("Installer", $profiler->name. " has installed new plugin: $name", "important");
            $notifications->pushNotification();

            return $name." was installed succesfully! You can reload now or wait 5 seconds.";
        }

        return "Invalid operation";
    }

    public function removePlugin($name, $id)
    {
        $this->rrmdir(PLUGINS.$name);
        unlink(PLUGINS.$name);
        $stmt = $this->mysqli->prepare("DELETE FROM `awt_plugins` WHERE `id` = ? AND `name` = ?;");
        $stmt->bind_param("is", $id, $name);
        $stmt->execute();
        $stmt->close();

        $profiler = new profiler;
        $notifications = new notifications("Installer", $profiler->name. " has removed plugin: $name", "important");
        $notifications->pushNotification();

        return true;
    }
}