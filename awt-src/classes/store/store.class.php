<?php

namespace store;

use content\pluginInstaller;
use content\themeInstaller;
use themes\themes;

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

    protected $response;

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

    protected function sendRequest()
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

        if ($this->type != '') {

            $result = $this->checkIfInstalled();

        } else {
            return false;
        }

        return $result;
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

    public function searchStore()
    {
        $this->sendRequest();
        return $this->checkIfInstalled();
    }


    private function checkIfInstalled()
    {

        global $plugins;
        $theme = new themes();
        $listThemes = $theme->getThemes();

        $result = json_decode($this->response, true);

        foreach ($result as $resKey => $res) {

            if (!version_compare(AWT_VERSION, $res['awt_version'], ">=")) {
                unset($result[$resKey]);
                continue;
            }

            foreach ($plugins as $key => $plugin) {
                if (strtolower(str_replace(' ', '', $res['name'])) == strtolower(str_replace(' ', '', $plugin['name']))) {
                    $result[$resKey]['installed'] = true;
                }
            }

            foreach ($listThemes as $key => $theme) {
                if (strtolower(str_replace(' ', '', $res['name'])) == strtolower(str_replace(' ', '', $theme['name']))) {
                    $result[$resKey]['installed'] = true;
                }
            }

        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }

}
