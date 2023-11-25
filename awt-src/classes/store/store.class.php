<?php

namespace store;

use content\pluginInstaller;

use ZipArchive;

class store {

    private object $settings;

    private object $database;

    private object $mysqli;

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


        $this->url = "http://marketplace.advanced-web-tools.com/";

        $this->data = ['api' => $this->api, 'package' => $this->package, 'type' => $this->type];
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
        $this->sendRequest();

        return $this->response;
    }

    public function updateAWTVersion()
    {
        $this->data['api'] = "getLatestAWTVersion";

        $this->sendRequest();
        $this->response = json_decode($this->response, true);

        /*

            THIS IS TEMPORARY SOLUTION UNTIL WE GET DOMAIN FOR AWT

        */

        $this->response[0]["path"] = "https://github.com/ElStefanos/Advanced-Web-Tools/releases/download/latest/release.zip";

        file_put_contents(TEMP . DIRECTORY_SEPARATOR . "update.zip", fopen($this->response[0]["path"], 'r'));

        $zip = new ZipArchive();

        $zip->open(TEMP . DIRECTORY_SEPARATOR .'update.zip');

        $zip->deleteName("awt-src/classes/database/");

        $zip->deleteName("awt-config.php");

        $zip->extractTo(ROOT);

        $zip->close();

        unlink(TEMP . DIRECTORY_SEPARATOR .'update.zip');

    }

    private function updatePlugin()
    {

    }

    private function updateTheme()
    {

    }

    private function createTable()
    {

    }

    private function modifyTable()
    {

    }
}