<?php

namespace packages\manager\loader;

use Exception;
use object\ObjectHandler;
use packages\manager\PackageManager;
use packages\runtime\api\RuntimeAPI;
use packages\runtime\handler\enums\ERuntimeExceptions;
use packages\runtime\handler\RuntimeHandler;
use packages\runtime\Runtime;
use ReflectionClass;
use ReflectionException;

/**
 * Class Loader
 *
 * The Loader class is responsible for managing the loading process of runtime packages within
 * the application. It extends the RuntimeHandler class and utilizes the PackageManager to
 * fetch and organize active packages. The Loader coordinates the execution order of runtime
 * packages, handling dependencies and potential circular loading issues. It maintains a list
 * of loaded packages and manages the loading state of each package in the system.
 */
final class Loader extends RuntimeHandler
{
    private PackageManager $packageManager;
    public array $loadingList = [];
    public array $active = [];
    public array $loaded = [];
    public array $waitListTrack = [];

    private array $waitListOrder = [];


    /**
     * Loader constructor.
     *
     * Initializes the Loader instance, fetching active packages from the PackageManager
     * and setting up the initial loading list.
     */
    public function __construct()
    {
        parent::__construct();

        $this->packageManager = new PackageManager();
        $this->packageManager->fetchPackages();

        $this->active = $this->packageManager->getActive();
        $this->loadingList = $this->active;
    }

    /**
     * Loads the packages in the loading list.
     *
     * Iterates through the loading list, extracts package objects,
     * and manages the loading process, ensuring any waiting packages
     * are handled correctly and executing the runtime for each package.
     * @throws Exception
     */
    public function load(): void
    {
        foreach ($this->loadingList as $key => $plugin) {
            if (!$plugin instanceof Runtime)
                $this->exception(ERuntimeExceptions::NotRuntime, "Unknown plugin");

            unset($this->loadingList[$key]);

            $this->resetRuntimeHandler();

            $pluginObjects = $this->extractPackageObject($plugin);

            foreach ($pluginObjects as $runtime) {
                $this->runtimeCreator($runtime, $plugin);

                if ($this->waitForRuntime) {
                    $this->waitListOrder[$this->runtime->name] = $this->runtime->waitList;

                    if ($this->checkForCircularWaiting()) {
                        $this->exception(ERuntimeExceptions::CircularError, $this->runtime->name);
                    }

                    if (!$this->handleLoadOrder($plugin))
                        break;
                }

                $this->execute();

                $this->loaded[$this->runtime->name] = $this->runtime;
            }
        }

    }

    /**
     * Checks if a waited package has already been loaded.
     *
     * @return bool True if the package is loaded; otherwise, false.
     */
    private function checkForLoaded(): bool
    {
        foreach ($this->runtime->waitList as $package) {
            if ($this->loaded[$package]) {
                continue;
            }
            return false;
        }
        return true;
    }

    /**
     * Handles the load order of runtime packages.
     *
     * Checks if the package has all its dependencies satisfied.
     * If not, it tracks the number of load attempts and may
     * re-add the package to the loading list if the maximum attempts
     * have not been reached.
     *
     * @param Runtime $runtime The runtime package being loaded.
     * @return bool True if the package is ready to be loaded; otherwise, false.
     */
    private function handleLoadOrder(Runtime $runtime): bool
    {
        if ($this->checkForLoaded())
            return true;

        if (!$this->awaitListCheck()) {
            if (!isset($this->waitListTrack[$this->runtime->name])) {
                $this->waitListTrack[$this->runtime->name] = 1;
            } else {
                $this->waitListTrack[$this->runtime->name]++;
            }

            if ($this->waitListTrack[$this->runtime->name] < PACKAGE_MAX_LOAD_TRY) {
                $this->loadingList[] = $runtime;
            }

            return false;
        }

        return true;
    }

    /**
     * Checks for circular waiting dependencies between packages.
     *
     * @return bool True if a circular dependency is detected; otherwise, false.
     */
    private function checkForCircularWaiting(): bool
    {
        foreach ($this->waitListOrder as $name => $waitList) {
            foreach ($waitList as $waitingPackage) {
                if (isset($this->waitListOrder[$waitingPackage]) && in_array($name, $this->waitListOrder[$waitingPackage])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Checks if all packages in the wait list are available for loading.
     *
     * @return bool True if all required packages are present; otherwise, false.
     */
    private function awaitListCheck(): bool
    {
        foreach ($this->runtime->waitList as $package) {
            if (!key_exists($package, $this->loadingList))
                return false;
        }

        return true;
    }


    /**
     * Extracts package objects from a given runtime package.
     *
     * Loads the main file of the runtime package and retrieves any classes
     * that extend RuntimeAPI. Instantiates and returns these classes.
     *
     * @param Runtime $runtime The runtime package to extract objects from.
     * @return array An array of instantiated package objects.
     * @throws Exception On invalid runtime path. On Reflection error.
     */
    public function extractPackageObject(Runtime $runtime): array
    {
        $className = ObjectHandler::createObjectFromFile(PACKAGES . str_replace(" ", "", $runtime->name) . DIRECTORY_SEPARATOR . "main.php");

        if (is_subclass_of($className, RuntimeAPI::class)) {
            try {
                $reflectionClass = new ReflectionClass($className);
            } catch (ReflectionException $e) {
                die("AWT Runtime: " . $e->getMessage());
            }
            if (!$reflectionClass->isAbstract()) {
                $return[] = new $className();
            }
        }

        if (!empty($return))
            return $return;

        $this->exception(ERuntimeExceptions::MissingRuntimeAPI, $runtime->name);
    }
}
