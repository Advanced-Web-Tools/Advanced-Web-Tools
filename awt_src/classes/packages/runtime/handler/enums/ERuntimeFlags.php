<?php

namespace packages\runtime\handler\enums;

/**
 * Enum ERuntimeFlags
 *
 * This enum defines various runtime flags that can be used within the AWT runtime environment.
 * Each case represents a specific flag with a particular purpose in the system.
 */
enum ERuntimeFlags
{
    /**
     * Flag to allow access to other RuntimeAPI instances
     */
    case AccessOtherInstances;

    /**
     * Flag for creating passable object of current RuntimeAPI instance.
     */
    case CreatePassableObject;

    /**
     * Flag for linking RuntimeAPI files.
     */
    case RuntimeLinker;

    /**
     * Flag for providing controllers.
     */
    case Controller;

    /**
     *  Flag for providing routers.
     */
    case Router;

    /**
     * Flag for EventDispatcher usage.
     */
    case EventDispatcher;

    /**
     * Prevents runtime execution until certain package is loaded.
     * Number of tried executions is marked with `PLUGIN_MAX_LOAD_TRY` constant. (Default 5.)
     */
    case WaitForPackage;

    /**
     * Tells RuntimeHandler that this package provides CLI commands.
     *
     */
    case CommandProvider;

}
