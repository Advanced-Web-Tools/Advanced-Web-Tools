<?php

namespace Quil\classes\editor\runtime\api;

use packages\runtime\api\RuntimeAPI;
use packages\runtime\handler\enums\ERuntimeFlags;
use Quil\classes\editor\event\EQuilEditor;
use Quil\classes\editor\event\EQuilEditorListener;
use Quil\classes\editor\runtime\interface\IQuilEditor;

/**
 * # Class QuilEditorAPI
 *
 *  - Part of `Quil` package.
 *  - Use with `Dashboard` package.
 *
 * Provides a way for other packages to create custom blocks and options.
 *
 * Extends a RuntimeAPI and implement IQuilEditor
 *
 * ## Usage
 *  - Wait for a "Quil" package to be loaded.
 *  - Should be a linked runtime (in main.php).
 *  - Use `EQuilEditorListener` event to provide your js "Quil" files.
 *  - Listen for "quil.editor.request" event.
 *  - Main automatically does everything for you just add scripts in Setup().
 */
abstract class QuilEditorAPI extends RuntimeAPI implements IQuilEditor
{
    protected array $scriptPaths;
    protected EQuilEditorListener $listener;

    public function environmentSetup(): void
    {
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::CreatePassableObject);
    }

    abstract public function setup(): void;

    /**
     * Adds a js file to Quil page builder.
     *
     * ### Important!!!!
     * *ALL QUIL SCRIPTS SHOULD BE PLACED IN THE ROOT DIRECTORY OF YOUR PACKAGE UNDER "Quil" DIRECTORY!*
     *
     * @param string $name Identification
     * @param string $path Cant only contain name of the files since .js is appended at the end.
     * @return void
     */
    public function addScriptPath(string $name, string $path): void
    {
        $this->scriptPaths[$name] = "awt_packages/" . $this->name . "/Quil/" . $path . ".js";
    }

    public function getScripts(): array
    {
        return $this->scriptPaths;
    }


    public function main(): void
    {
        $this->eventDispatcher->addListener("quil.editor.request", new EQuilEditorListener($this->scriptPaths));
    }
}