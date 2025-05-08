<?php

namespace packages\runtime\handler;

use event\EventDispatcher;
use object\ObjectHandler;
use packages\runtime\api\RuntimeAPI;
use packages\runtime\interface\IRuntime;
use packages\runtime\handler;
use packages\runtime\handler\enums\ERuntimeFlags;
use packages\runtime\handler\enums\ERuntimeExceptions;
use packages\runtime\Runtime;
use ReflectionClass;
use ReflectionException;

/**
 * Class RuntimeHandler
 *
 * The RuntimeHandler class manages the lifecycle and execution of runtime instances.
 * It handles the creation, environment setup, and execution of various runtime packages
 * while managing flags and linked classes.
 */
class RuntimeHandler extends RuntimeExceptions
{

    protected RuntimeAPI $runtime;
    protected bool $isLinker;
    protected bool $isRouter;
    protected bool $isPassable;
    protected bool $usesEvents;
    protected bool $waitForRuntime;
    public array $passableInstances;
    public array $routers;
    public array $sharedObjects = [];

    public EventDispatcher $eventDispatcher;

    public function __construct()
    {
        $this->isLinker = false;
        $this->isRouter = false;
        $this->isPassable = false;
        $this->passableInstances = [];
        $this->routers = [];
    }

    /**
     * Creates and sets up a new runtime instance.
     *
     * @param RuntimeAPI $package The runtime API instance to be created.
     * @param Runtime $origin The origin runtime object.
     * @return IRuntime The newly created runtime instance.
     */
    public function runtimeCreator(RuntimeAPI $package, Runtime $origin): IRuntime
    {
        $this->createRuntimeInstance($package, $origin);
        $this->setupEnvironment();
        $this->flagHandler();

        $this->setup();

        return $this->runtime;
    }

    /**
     * Creates a runtime instance and sets its information.
     *
     * @param RuntimeAPI $runtimeAPI The runtime API instance.
     * @param Runtime $origin The origin runtime object.
     * @return RuntimeAPI The created runtime API instance.
     */
    protected function createRuntimeInstance(RuntimeAPI $runtimeAPI, Runtime $origin): RuntimeAPI
    {
        $runtimeAPI->setInfo($origin);
        $this->runtime = $runtimeAPI;
        return $this->runtime;
    }

    /**
     * Sets up the environment for the current runtime instance.
     */
    protected function setupEnvironment(): void
    {
        $this->runtime->environmentSetup();
    }

    /**
     * Performs additional setup for the current runtime instance.
     */
    protected function setup(): void
    {
        $this->runtime->setSharable($this->sharedObjects);
        $this->runtime->setup();
    }

    /**
     * Executes the main logic of the runtime.
     * Features:
     * - Create passable instance if applicable.
     * - Handle event dispatching if the runtime uses events.
     * - Store the runtime in the routers array if it's a router provider.
     * - Handle linked runtimes if it's a linker.
     * @return void
     */
    public function execute(): void
    {
        $this->runtime->main();
        $this->createPassable();
        $this->sharedObjects = $this->runtime->shared;
        if($this->usesEvents) {
            $this->eventDispatcher = $this->runtime->eventDispatcher;
        }

        if ($this->isRouter) {
            $this->routers[] = $this->runtime;
        }

        if ($this->isLinker) {
            $objects = $this->loadLinked($this->runtime);
            foreach ($objects as $object) {
                $this->runtimeCreator($object, $this->runtime);
                $this->execute();
            }
        }
    }

    /**
     * Handles runtime flags to configure the current runtime instance.
     */
    public function flagHandler(): void
    {
        if (!$this->runtime->hasFlags()) {
            return;
        }

        foreach ($this->runtime->configurationFlags as $flag) {
            switch ($flag) {
                case ERuntimeFlags::RuntimeLinker:
                    $this->isLinker = true;
                    break;
                case ERuntimeFlags::CreatePassableObject:
                    $this->isPassable = true;
                    break;
                case ERuntimeFlags::AccessOtherInstances:
                    $this->runtime->passable = $this->passableInstances;
                    break;
                case ERuntimeFlags::Router:
                    $this->isRouter = true;
                    break;
                case ERuntimeFlags::EventDispatcher:
                    $this->runtime->eventDispatcher = $this->eventDispatcher;
                    $this->usesEvents = true;
                    break;
                case ERuntimeFlags::WaitForPackage:
                    $this->waitForRuntime = true;
                    break;
            }
        }
    }

    /**
     * Creates a passable instance of the current runtime if applicable.
     */
    private function createPassable(): void
    {
        if ($this->isPassable)
            $this->passableInstances[$this->runtime->name][get_class($this->runtime)] = $this->runtime;
    }

    /**
     * Loads linked runtimes defined in the current runtimes links.
     *
     * @param RuntimeAPI $runtime The runtime instance to load links from.
     * @return array An array of loaded linked runtime instances.
     * @throws \Exception
     */
    public function loadLinked(RuntimeAPI $runtime): array
    {
        $return = [];

        if (empty($runtime->links))
            return $return;

        foreach ($runtime->links as $link) {

            $extractedObject = ObjectHandler::createObjectFromFile($link);

            if ($extractedObject === null)
                return $return;

            if (is_subclass_of($extractedObject, RuntimeAPI::class)) {
                try {
                    $reflectionClass = new ReflectionClass($extractedObject);
                } catch (ReflectionException $e) {
                    die("RuntimeHandler: " . $e->getMessage());
                }
                if (!$reflectionClass->isAbstract()) {
                    $return[] = $extractedObject;
                }
            }

            if (empty($return))
                $this->exception(ERuntimeExceptions::LinkerMissingRuntimeAPI, $runtime->name);
        }

        return $return;
    }


    /**
     * Resets the flags and states of the RuntimeHandler.
     */
    protected function resetRuntimeHandler(): void
    {
        $this->isLinker = false;
        $this->isRouter = false;
        $this->isPassable = false;
        $this->usesEvents = false;
        $this->waitForRuntime = false;
    }

}