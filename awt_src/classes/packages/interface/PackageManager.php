<?php

namespace packages\interface;

/**
 * Interface PackageManager
 *
 * The PackageManager interface defines the methods required for managing
 * the lifecycle of packages within the system. Implementing classes must
 * provide functionality for actions that occur during the installation,
 * updating, and uninstallation of packages. This ensures a consistent
 * approach to package management throughout the application.
 */
interface PackageManager
{
    /**
     * Performs actions after a package is installed.
     *
     * This method should contain logic that needs to be executed
     * immediately after a package has been successfully installed,
     * such as configuring settings or initializing resources.
     */
    public function postInstall();

    /**
     * Performs actions after a package is updated.
     *
     * This method should include any necessary operations that
     * need to be carried out following the successful update of a
     * package, such as migrating data or refreshing dependencies.
     */
    public function postUpdate();

    /**
     * Performs actions after a package is uninstalled.
     *
     * This method is responsible for handling cleanup and
     * resource deallocation after a package has been uninstalled,
     * ensuring that any residual data is properly removed.
     */
    public function postUninstall();

    /**
     * Performs actions before a package is installed.
     *
     * This method should include any preparations or checks that
     * need to occur prior to the installation of a package, such as
     * verifying prerequisites or ensuring compatibility.
     */
    public function preInstall();
}