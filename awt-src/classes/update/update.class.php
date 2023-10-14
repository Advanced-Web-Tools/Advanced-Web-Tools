<?php

namespace update;

class update {

    private object $settings;

    private object $database;

    private object $mysqli;

    private string $package;

    private string $type;

    public string $url;

    private string $response;

    private array $data;

    private string $api;

    public int $avaliablePluginUpdatesNumber;

    public int $avaliableThemeUpdatesNumber;

    public function __construct(string $apiCall, string $package, string $type)
    {

        $this->api = $apiCall;
        $this->package = $package;
        $this->type = $type;


        $this->url = "http://localhost/AWT-Store/";

        $this->data = ['api' => $this->api, 'package' => $this->package, 'type' => $this->type];
    }

    private function sendRequest()
    {
        $fields_string = http_build_query($this->data);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $this->response = $result;
    }


    public function searchPackage()
    {
        $this->sendRequest();

        return $this->response;

    }

    private function updateAWTVersion()
    {

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