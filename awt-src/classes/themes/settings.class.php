<?php

namespace themes;

use database\databaseConfig;
use XMLReader;

class settings extends themes
{

    public array $settings;
    private XMLReader $XMLReader;
    private databaseConfig $dataBase;
    private object $mysqli;

    protected string $settingsPath;

    public function __construct()
    {
        parent::__construct();
        $this->getActiveTheme();
        $this->settings = array();

        $this->dataBase = new databaseConfig();

        $this->dataBase->checkAuthority() == 1 or die("Fatal error database access for " . $this->dataBase->getCaller() . " was denied");
        $this->mysqli = $this->dataBase->getConfig();

        $this->settingsPath = THEMES . $this->activeTheme['name'] . DIRECTORY_SEPARATOR . 'settings.xml';

    }

    public function checkForSettings() : bool
    {
        if(!file_exists($this->settingsPath)) return false;
        return true;
    }

    public function getSettings() : bool
    {

        if($this->readSettingsFromXML())
        {
            $this->getSettingsFromDatabase();
            return true;
        }


        return false;

    }

    public function readSettingsFromXML() : bool
    {
        $this->XMLReader = new XMLReader();

        if(!$this->checkForSettings()) return false;

        $this->XMLReader->open($this->settingsPath);

        while ($this->XMLReader->read()) {
            if ($this->XMLReader->nodeType == XMLReader::END_ELEMENT || $this->XMLReader->name == "settings" ||  $this->XMLReader->name == "#text") {
                continue;
            }
            $this->settings[$this->XMLReader->name]['value'] = $this->XMLReader->readInnerXml();
            $this->settings[$this->XMLReader->name]['type'] = $this->XMLReader->getAttribute('type');
            $this->settings[$this->XMLReader->name]['category'] = $this->XMLReader->getAttribute('category');
            $this->settings[$this->XMLReader->name]['placeholder'] = $this->XMLReader->getAttribute('placeholder');
        }

        return true;
    }


    public function getSettingsFromDatabase()
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_theme_settings` WHERE `theme_id` = ?");
        $themeID = (int) $this->activeTheme['id'];
        $stmt->bind_param("i", $themeID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($result as $key => $value) {
            if(array_key_exists($value['name'] ,$this->settings)){ 
                $this->settings[$value['name']] = $value;
            }
        }
    }


    public function checkForSettingInDatabase(string $name) : int
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_theme_settings` WHERE `theme_id` = ? AND `name` = ?");
        $stmt->bind_param("is", $this->activeTheme['id'], $name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $numRows = $stmt->num_rows;
        $stmt->close();
        return $numRows;
    }

    public function changeSetting(string $name, string $value)
    {   

        $check = $this->checkForSettingInDatabase($name);
        if($check) {
            $sql = "UPDATE `awt_theme_settings` SET `value` = ? WHERE `name` = ? AND `theme_id` = ?;";
        } else {
            $sql = "INSERT INTO `awt_theme_settings` (`theme_id`, `category`, `name`, `type`, `value`) VALUES (?, ?, ?, ?, ?);";
        }

        $stmt = $this->mysqli->prepare($sql);

        if($check) {
            $stmt->bind_param("ssi", $value, $name, $this->activeTheme['id']);
        } else {
            $stmt->bind_param("issss", $this->activeTheme['id'], $this->settings[$name]['category'], $name, $this->settings[$name]['type'], $value);
        }

        $stmt->execute();
        $stmt->close();


    }

    public function revertToOriginal(int $id)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM `awt_theme_settings` WHERE `id` = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function getSetting(string $name) : array
    {
        return $this->settings[$name];
    }


}