<?php

namespace packages\runtime\handler;

use JetBrains\PhpStorm\NoReturn;
use packages\runtime\handler\enums\ERuntimeExceptions;

/**
 * Class RuntimeExceptions
 *
 * This abstract class provides methods to handle runtime exceptions
 * that may occur in the AWT runtime environment. Each exception is
 * represented by the ERuntimeExceptions enum.
 */
abstract class RuntimeExceptions
{
    /**
     * Handles exceptions based on the provided ERuntimeExceptions value.
     *
     * @param ERuntimeExceptions $runtimeExceptions The specific runtime exception to handle.
     * @param string $name The name of the package or component causing the exception.
     */
    #[NoReturn] public function exception(ERuntimeExceptions $runtimeExceptions, string $name): void
    {
        switch ($runtimeExceptions) {
            case ERuntimeExceptions::InvalidFlag:
                die("AWT Runtime Error: Invalid flag in runtime <br> Name: " . htmlspecialchars($name));

            case ERuntimeExceptions::CircularError:
                die("AWT Runtime Fatal Error: Some packages are waiting for each other to be loaded! This is a circular loading behaviour and is preventing AWT from loading them! <br> Name: " . htmlspecialchars($name));

            case ERuntimeExceptions::NotRuntime:
                die("AWT Runtime Fatal Error: Non-runtime object was provided.<br> Name: " . htmlspecialchars($name));

            case ERuntimeExceptions::MissingRuntimeAPI:
                die("AWT Runtime Fatal Error: Runtime tried to initialize RuntimeAPI, but it was not found.<br> Name: " . htmlspecialchars($name));

            case ERuntimeExceptions::LinkerMissingRuntimeAPI:
                die("AWT Runtime Fatal Error: Runtime linker provided non-RuntimeAPI files. <br> Name: " . htmlspecialchars($name));

            default:
                die("AWT Runtime Error: An unknown runtime error occurred.<br> Name: " . htmlspecialchars($name));
        }
    }
}
