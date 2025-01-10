<?php
namespace packages\runtime\interface;
use packages\runtime\Runtime;

/**
 * Interface IRuntime
 *
 * This interface defines the structure for runtime implementations in the AWT.
 * Any class implementing this interface must provide the specified methods to manage the runtime environment
 * and package lifecycle.
 */
interface IRuntime {

    /**
     * Sets the information for the runtime instance.
     *
     * @param Runtime $runtime The Runtime instance containing information.
     */
    public function setInfo(Runtime $runtime);

    /**
     * Prepares the environment for the runtime.
     *
     * This method is responsible for setting up any necessary configurations or resources
     * before the main execution of the runtime.
     */
     public function environmentSetup() : void;

    /**
     * Performs additional setup tasks for the runtime.
     *
     * This method is called after the environment setup to perform any further initialization
     * required by the runtime.
     */
    public function setup(): void;

    /**
     * Executes the main logic of the runtime.
     *
     * This method contains the core functionality that the runtime is responsible for.
     */
    public function main(): void;

 }