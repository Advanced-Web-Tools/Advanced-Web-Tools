<?php

namespace packages\runtime\handler\enums;


/**
 * Enum ERuntimeExceptions
 *
 * This enum defines various runtime exceptions that can occur within the AWT runtime environment.
 * Each case represents a specific type of runtime exception that may be raised during execution.
 */
enum ERuntimeExceptions
{
    /**
     * Exception thrown when an invalid flag is encountered during runtime.
     */
    case InvalidFlag;

    /**
     * Exception thrown when a circular dependency error occurs, indicating that some packages are waiting
     * for each other to be loaded.
     */
    case CircularError;

    /**
     * Exception thrown when a non-runtime object is provided where a runtime object is expected.
     */
    case NotRuntime;

    /**
     * Exception thrown when the runtime attempts to initialize the RuntimeAPI, but it is not found.
     */
    case MissingRuntimeAPI;

    /**
     * Exception thrown when the linker provided non-runtime API files, indicating a configuration issue.
     */
    case LinkerMissingRuntimeAPI;
}

