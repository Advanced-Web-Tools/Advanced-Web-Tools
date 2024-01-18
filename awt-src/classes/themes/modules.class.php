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
        global $render;

        $data = $additional_data;
        ob_start();

        include_once $this->requiredModules[$name];

        $contents = ob_get_contents();

        ob_end_clean();
            
        return $contents;
    }

}
