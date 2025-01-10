<?php

namespace packages\runtime\api;

use Error;
use event\EventDispatcher;
use packages\runtime\handler\enums\ERuntimeFlags;
use packages\runtime\interface\IRuntime;
use packages\runtime\Runtime;
use ReflectionClass;
use ReflectionException;
use UnitEnum;

#[\AllowDynamicProperties]
/**
 * Abstract class RuntimeAPI
 *
 * The RuntimeAPI class represents the base for runtime packages,
 * providing methods to manage configurations, events, and passable instances.
 */

abstract class RuntimeAPI extends Runtime implements IRuntime
{
    public array $passable = [];
    public array $configurationFlags = [];
    public array $waitList = [];
    protected string $runtimePath;

    public EventDispatcher $eventDispatcher;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sets information from the provided plugin into the current runtime instance.
     *
     * @param Runtime $runtime The runtime plugin to extract information from.
     */
    final public function setInfo(Runtime $runtime): void
    {
        foreach (get_object_vars($runtime) as $property => $value) {
            if ($value instanceof UnitEnum) {
                $this->$property = $value;
            } elseif (is_object($value)) {
                try {
                    $this->$property = clone $value;
                } catch (Error $e) {
                    $this->$property = $value;
                }
            } else {
                $this->$property = $value;
            }
        }

        $this->runtimePath = PACKAGES . str_replace(" ", "", $this->name);
    }

    /**
     * Checks if there are any configuration flags set.
     *
     * @param bool $hasFlags (Optional) Flag indicating the check mode.
     * @return bool True if there are flags; otherwise, false.
     */
    final public function hasFlags(bool $hasFlags = false): bool
    {
        return !empty($this->configurationFlags);
    }

    /**
     * Sets a configuration flag for the runtime.
     *
     * @param ERuntimeFlags $flag The flag to set.
     */
    final public function setRuntimeFlag(ERuntimeFlags $flag): void
    {
        $this->configurationFlags[] = $flag;
    }

    /**
     * Retrieves a passable instance by runtime name and class name.
     *
     * @param string $runtimeName The name of the runtime.
     * @param string $className The name of the class to retrieve.
     * @param string|null $type (Optional) The expected type of the instance.
     * @return object|null The passable instance if found; otherwise, null.
     */
    final protected function getPassable(string $runtimeName, string $className, $type = null): ?object
    {

        if (!array_key_exists($runtimeName, $this->passable))
            return null;

        if (!array_key_exists($className, $this->passable[$runtimeName]))
            return null;

        if ($type === null)
            return $this->passable[$runtimeName][$className];

        if ($this->passable[$runtimeName][$className] instanceof $type)
            return $this->passable[$runtimeName][$className];

        return null;
    }


    /**
     * Loads a and creates local object defined in the specified path relative to the runtime path.
     *
     * @param string $pathFromRoot The relative path to load the object from.
     * @return object|null The loaded object if successful; otherwise, null.
     */
    final protected function getLocalObject(string $pathFromRoot): ?object
    {
        $loadedClasses = get_declared_classes();

        require_once $this->runtimePath . $pathFromRoot;

        $newClasses = get_declared_classes();

        $newClasses = array_diff($newClasses, $loadedClasses);

        foreach ($newClasses as $class) {
            try {
                $reflection = new ReflectionClass($class);
            } catch (ReflectionException $e) {
                break;
            }

            if (!$reflection->isAbstract()) {
                return new $class();
            }
        }

        return null;
    }

    /**
     * Adds a runtime name to the wait list, indicating it is awaited.
     *
     * @param string $name The name of the runtime to wait for.
     */
    final protected function waitForRuntime(string $name): void
    {
        $this->waitList[] = $name;
    }

}