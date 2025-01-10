<?php

namespace packages\runtime\api;

use packages\runtime\handler\enums\ERuntimeFlags;

/**
 * Abstract class RuntimeLinkerAPI
 *
 * The RuntimeLinkerAPI class extends RuntimeAPI and is responsible for managing
 * links to external runtime components of the same package. It provides methods to
 * set up the runtime environment for linking and to create links.
 */
abstract class RuntimeLinkerAPI extends RuntimeAPI
{
    public array $links = [];

    /**
     * Sets up the environment for the runtime linker.
     *
     * This method configures the runtime by setting the necessary flags
     * for linking and creating passable objects.
     */
    public function environmentSetup(): void
    {
        $this->setRuntimeFlag(ERuntimeFlags::RuntimeLinker);
        $this->setRuntimeFlag(ERuntimeFlags::CreatePassableObject);
    }

    /**
     * Creates a link to an external runtime of same package.
     *
     * @param string $name The name of the link.
     * @param string $pathFromRoot The path to the resource from the root of the package.
     */
    final public function createLink(string $name, string $pathFromRoot): void
    {
        $this->links[$name] = $this->runtimePath . $pathFromRoot;
    }
}