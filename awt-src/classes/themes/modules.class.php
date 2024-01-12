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
        global $theme;
        $data = $additional_data;
        include_once $this->requiredModules[$name];
    }

}
