<?php

namespace plugins;

use admin\profiler;
use database\databaseConfig;
use content\writeXml;
use notifications\notifications;
use XMLReader;


class plugins
{
    private object $database;
    private object $mysqli;
    private object $xml;
    private object $writeXml;
    protected array $info;

    public function __construct()
    {
        $this->database = new databaseConfig;

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");
        $this->mysqli = $this->database->getConfig();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_plugins` ORDER BY `id` ASC;");
        $stmt->execute();
        $this->info = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($this->info as $key => $plugin) {

            if (file_exists(PLUGINS . $plugin['name'] . DIRECTORY_SEPARATOR . 'plugin.main.php')) {

                if (file_exists(PLUGINS . $plugin['name'] . DIRECTORY_SEPARATOR . 'plugin.xml')) {

                    $this->xml = new XMLReader;
                    $this->xml->open(PLUGINS . $plugin['name'] . DIRECTORY_SEPARATOR . 'plugin.xml');

                    while ($this->xml->read()) {
                        if ($this->xml->name != 'plugin' && $this->xml->name != '#text') {
                            if ($this->xml->nodeType == XMLReader::END_ELEMENT) {
                                continue;
                            }
                            if ($this->xml->name == 'authorizationFile') {

                                $file = explode(' ', $this->xml->readString());

                                $this->info[$key]['xml'][$this->xml->name] = '';

                                foreach ($file as $file) {
                                    $this->info[$key]['xml'][$this->xml->name] .= '|' . PLUGINS . $plugin['name'] . DIRECTORY_SEPARATOR . str_replace('.SPRT.', DIRECTORY_SEPARATOR, $file);
                                }
                            } else {
                                $this->info[$key]['xml'][$this->xml->name] = $this->xml->readString();
                            }
                        }
                    }
                }
            } else {
                unset($this->info[$key]);
                array_values($this->info);
            }

            $this->writeXml = new writeXml;

        }
    }

    public function getPlugins()
    {
        return $this->info;
    }

    public function changeStatus(int $id, string $value)
    {
        if ($id == '' || $value == '') return false;

        $stmt = $this->mysqli->prepare('UPDATE `awt_plugins` SET `status` = ? WHERE `id` = ?;');
        $stmt->bind_param('ss', $value, $id);
        $stmt->execute();
        $stmt->close();

        $profiler = new profiler;
        $notifications = new notifications("Plugins", $profiler->name. " has changed plugins status with ID: $id to  $value", "notice");
        $notifications->pushNotification();

        return true;
    }


    public function authorizeDatabase(string $action, string $file, string $name)
    {
        $value = 'true';

        $return = false;

        if ($action == 'authorize') $value = 'false';

        $files = explode('|', $file);

        $files = array_filter($files);

        foreach ($files as $file) {

            $key = $this->database->getSecretKey();

            $hash = hash_file('SHA512', $file);

            $return = $this->database->authorizeUsage($action, $file, $hash, $key);

            if ($return == true) {
                $this->writeXml->changePluginXml($name, 'requiresAuthorization', $value);
            }
        }

        return $return;
    }
}
