<?php

namespace store;

use content\pluginInstaller;
use content\themeInstaller;
use themes\themes;

use database\databaseConfig;
use ReflectionClass;
use ZipArchive;

class store
{

    private object $settings;

    private object $database;

    private object $mysqli;

    private themeInstaller $themeInstaller;

    private pluginInstaller $pluginInstaller;

    private string $package;

    private string $type;

    public string $url;

    private $response;

    private array $data;

    private string $api;

    public int $avaliablePluginUpdatesNumber;

    public int $avaliableThemeUpdatesNumber;

    public function __construct(string $apiCall = "", string $package = "", string $type = "")
    {

        $this->api = $apiCall;
        $this->package = $package;
        $this->type = $type;


        $this->url = "https://store.advancedwebtools.com/api.php";

        $this->data = ['api' => $this->api, 'package' => $this->package, 'type' => $this->type];

        $this->pluginInstaller = new pluginInstaller();

        $this->themeInstaller = new themeInstaller();
    }

    private function sendRequest()
    {
        $fields_string = http_build_query($this->data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($ch);

        curl_close($ch);
        $this->response = $result;
    }

    public function searchPackage()
    {
        global $plugins;
        $theme = new themes();

        $listThemes = $theme->getThemes();

        $this->sendRequest();

        if ($this->type != '') {

            $result = json_decode($this->response, true);

            foreach ($result as $resKey => $res) {

                if(!version_compare(AWT_VERSION, $res['awt_version'], ">=")) {
                    unset($result[$resKey]);
                    continue;
                }

                if($this->type == "plugin") {
                    foreach ($plugins as $key => $plugin) {
                        if (strtolower(str_replace(' ', '', $res['name'])) == strtolower(str_replace(' ', '', $plugin['name']))) {
                            $result[$resKey]['installed'] = true;
                        }
                    }
                }

                if($this->type == "theme") {
                    foreach ($listThemes as $key => $theme) {
                        if (strtolower(str_replace(' ', '', $res['name'])) == strtolower(str_replace(' ', '', $theme['name']))) {
                            $result[$resKey]['installed'] = true;
                        }
                    }
                }

            }
        } else {
            return false;
        }

        $result = json_encode($result);

        return $result;
    }

    public function updateAWTVersion()
    {
        $this->data['api'] = "getLatestAWTVersion";

        $this->sendRequest();
        $this->response = json_decode($this->response, true);


        if ($versionCompare = version_compare(AWT_VERSION, $this->response[0]["version"]) == -1) {
            file_put_contents(TEMP . DIRECTORY_SEPARATOR . "update.zip", fopen($this->response[0]["path"], 'r'));

            $zip = new ZipArchive();

            $zip->open(TEMP . DIRECTORY_SEPARATOR . 'update.zip');

            $zip->extractTo(ROOT);

            $zip->close();

            $this->updateDatabase();

            $this->updateConfigFile();

            unlink(TEMP . DIRECTORY_SEPARATOR . 'update.zip');
        }
    }

    public function checkAWTVersion()
    {
        $this->data['api'] = "getLatestAWTVersion";

        $this->sendRequest();
        $this->response = json_decode($this->response, true);

        $versionCompare = version_compare(AWT_VERSION, $this->response[0]["version"]);
        $return['version_compare'] = $versionCompare;
        $return['latest'] = $this->response[0]['version'];
        return $return;
    }


    public function installPlugin(string $downloadPath)
    {

        $shortName = substr(hash('SHA512', $downloadPath), 0, 10);
        $path = TEMP . DIRECTORY_SEPARATOR . $shortName . ".zip";
        file_put_contents($path, fopen($downloadPath, 'r'));

        $return = $this->pluginInstaller->installFromStore($path);

        return $return;
    }

    public function installTheme(string $downloadPath)
    {
        $shortName = substr(hash('SHA512', $downloadPath), 0, 10);
        $path = TEMP . DIRECTORY_SEPARATOR . $shortName . ".zip";
        file_put_contents($path, fopen($downloadPath, 'r'));

        $return = $this->themeInstaller->installFromStore($path);

        return $return;
    }


    private function updatePlugin()
    {

    }

    private function updateTheme()
    {

    }

    private function getDatabaseConfig()
    {

        $db = new databaseConfig();

        if ($db->checkAuthority() == 1) {
            $reflectionClass = new ReflectionClass($db);
            $username = $reflectionClass->getProperty('username');
            $username->setAccessible(true);
            $info['username'] = $username->getValue();

            $password = $reflectionClass->getProperty('password');
            $password->setAccessible(true);
            $info['password'] = $password->getValue();

            $hostname = $reflectionClass->getProperty('hostname');
            $hostname->setAccessible(true);
            $info['hostname'] = $hostname->getValue();

            $database = $reflectionClass->getProperty('database');
            $database->setAccessible(true);
            $info['database'] = $database->getValue();

            $key = $reflectionClass->getProperty('key');
            $key->setAccessible(true);
            $info['key'] = $key->getValue();

            return $info;
        }

        return null;
    }

    private function updateDatabase()
    {

        if (file_exists(ROOT . DIRECTORY_SEPARATOR . 'awt-database.sql')) {
            $db = new databaseConfig();
            $db->checkAuthority();
            $mysql = $db->getConfig();
            $sql = file_get_contents(ROOT . DIRECTORY_SEPARATOR . 'awt-database.sql');
            $mysql->multi_query($sql);
        }

        $info = $this->getDatabaseConfig();

        $db_config_file = CLASSES . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'databaseConfig.class.php';

        $this->replaceFileContent($db_config_file, 'private static string $hostname = "";', 'private static string $hostname = "' . $info['hostname'] . '";');
        $this->replaceFileContent($db_config_file, 'private static string $database = "";', 'private static string $database = "' . $info['database'] . '";');
        $this->replaceFileContent($db_config_file, 'private static string $username = "";', 'private static string $username = "' . $info['username'] . '";');
        $this->replaceFileContent($db_config_file, 'private static string $password = "";', 'private static string $password = "' . $info['password'] . '";');
        $this->replaceFileContent($db_config_file, 'private static string $key = "";', 'private static string $key = "' . $info['key'] . '";');

    }

    private function replaceFileContent(string $file, string $old_content, string $new_content)
    {
        $lines = file($file);

        foreach ($lines as $line => $content) {
            if (str_contains($content, $old_content)) {
                $lines[$line] = $new_content . PHP_EOL;
            }
        }

        file_put_contents($file, implode("", $lines));
    }

    private function updateConfigFile()
    {
        $this->replaceFileContent(ROOT . DIRECTORY_SEPARATOR . 'awt-config.php', 'define(\'WEB_NAME\', "");', 'define("WEB_NAME", "' . WEB_NAME . '");');

        $this->replaceFileContent(ROOT . DIRECTORY_SEPARATOR . 'awt-config.php', 'define(\'AWT_VERSION\', "");', 'define("AWT_VERSION", "' . $this->response[0]['version'] . '");');

        $this->replaceFileContent(ROOT . DIRECTORY_SEPARATOR . 'awt-config.php', 'define("CONTACT_EMAIL", "");', 'define("CONTACT_EMAIL", "' . CONTACT_EMAIL . '");');
    }

}