<?php

namespace themes;

class modules
{

    public array $requiredModules;
    public function addModule($name, $path)
    {
        $this->requiredModules[$name] = $path;
    }

    public function loadModule($name, $additional_data = '')
    {
        $data = $additional_data;
        global $theme;
        include_once $this->requiredModules[$name];
    }

    private function read_element_data($element)
    {
        $elementData = array();

        foreach ($element->attributes() as $name => $value) {
            $elementData[$name] = (string)$value;
        }

        $textContent = trim((string)$element);

        if (!empty($textContent)) {
            $elementData['_text'] = $textContent;
        }

        foreach ($element->children() as $child) {
            $childName = $child->getName();

            if (array_key_exists($childName, $elementData)) {
                if (!is_array($elementData[$childName])) {
                    $elementData[$childName] = array($elementData[$childName]);
                }
                $elementData[$childName][] = $this->read_element_data($child);
            } else {
                $elementData[$childName] = $this->read_element_data($child);
            }
        }

        return $elementData;
    }


    public function getModuleData($filePath)
    {
        global $theme;
        $result = array();
        $xml = simplexml_load_file($filePath);

        foreach ($xml->module as $module) {
            $moduleName = (string)$module['name'];

            // Read attributes and child elements of each module
            $moduleData = array();

            foreach ($module->attributes() as $name => $value) {
                $moduleData[$name] = (string)$value;
            }

            foreach ($module->children() as $child) {
                $childName = $child->getName();

                // Check if child element is already an array key
                if (array_key_exists($childName, $moduleData)) {
                    if (!is_array($moduleData[$childName])) {
                        // Convert single child element into an array
                        $moduleData[$childName] = array($moduleData[$childName]);
                    }
                    $moduleData[$childName][] = $this->read_element_data($child);
                } else {
                    $moduleData[$childName] = $this->read_element_data($child);
                }
                $result[$moduleName] = $moduleData;
            }
        }

        return $result;
    }

    public function loadModulesByOrder($filePath)
    {
        global $theme;
        $result = array();
        $xml = simplexml_load_file($filePath);

        foreach ($xml->module as $module) {
            $moduleName = (string)$module['name'];

            // Read attributes and child elements of each module
            $moduleData = array();

            foreach ($module->attributes() as $name => $value) {
                $moduleData[$name] = (string)$value;
            }

            foreach ($module->children() as $child) {
                $childName = $child->getName();

                // Check if child element is already an array key
                if (array_key_exists($childName, $moduleData)) {
                    if (!is_array($moduleData[$childName])) {
                        // Convert single child element into an array
                        $moduleData[$childName] = array($moduleData[$childName]);
                    }
                    $moduleData[$childName][] = $this->read_element_data($child);
                } else {
                    $moduleData[$childName] = $this->read_element_data($child);
                }
            }

            // Assign module data to the result array
            $result[$moduleName] = $moduleData;
        }

        foreach ($result as $key => $value) {
            if (array_key_exists($key, $this->requiredModules) && $result[$key]['status'] == "Enabled") {
                $this->loadModule($key, $result[$key]);
            }
        }
    }
}
