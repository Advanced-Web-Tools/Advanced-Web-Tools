<?php

namespace Quil\classes\editor\runtime\interface;

/**
 * # Interface IQuilEditor
 *
 * - Adds a way for packages to communicate with "Quil".
 */
interface IQuilEditor
{
    /**
     * @param string $name Name of the script
     * @param string $path Path to the script
     * @return void
     */
    public function addScriptPath(string $name, string $path): void;

    /**
     * @return array A list of quil scripts
     */

    public function getScripts() : array;
}