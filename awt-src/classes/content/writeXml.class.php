<?php

namespace content;

class writeXml {
    public function changePluginXml($pluginName, $attributeName, $newValue)
    {
        // Load the XML file
        $xmlFilePath = PLUGINS . $pluginName . DIRECTORY_SEPARATOR . 'plugin.xml';
        $xml = simplexml_load_file($xmlFilePath);


        // Find the plugin element with the given name
        $pluginElements = $xml->xpath("//plugin[name='{$pluginName}']");
        if (count($pluginElements) > 0) {
            // Set the new attribute value
            $pluginElement = $pluginElements[0];
            $pluginElement->{$attributeName} = $newValue;

            // Save the updated XML file
            $return =  file_put_contents($xmlFilePath, $xml->asXML());
        }

        if ($return != false) return true;
        return false;
    }
}