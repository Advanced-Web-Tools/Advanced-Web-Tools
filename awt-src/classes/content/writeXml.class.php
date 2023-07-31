<?php

namespace content;

class writeXml {
    public function changePluginXml($pluginName, $attributeName, $newValue)
    {

        $xmlFilePath = PLUGINS . $pluginName . DIRECTORY_SEPARATOR . 'plugin.xml';
        $xml = simplexml_load_file($xmlFilePath);

        $pluginElements = $xml->xpath("//plugin[name='{$pluginName}']");
        if (count($pluginElements) > 0) {
            $pluginElement = $pluginElements[0];
            $pluginElement->{$attributeName} = $newValue;

            $return =  file_put_contents($xmlFilePath, $xml->asXML());
        }

        if ($return != false) return true;
        return false;
    }
}