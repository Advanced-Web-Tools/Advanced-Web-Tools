<?php

namespace object;

use Exception;

class ObjectHandler

{
    /**
     * Create an object from a PHP file containing a class declaration.
     *
     * @param string $filename The file path.
     * @return object|null Returns an instance of the newly declared class or null if no suitable class is found.
     * @throws Exception on file not found
     */
    public static function createObjectFromFile(string $filename): ?object
    {
        if (!file_exists($filename)) {
            throw new Exception("File not found: $filename");
        }

        $beforeClasses = get_declared_classes();

        include_once $filename;

        $afterClasses = get_declared_classes();

        $newClasses = array_diff($afterClasses, $beforeClasses);

        foreach ($newClasses as $className) {
            $reflection = new \ReflectionClass($className);

            if (!$reflection->isAbstract()) {
                return $reflection->newInstance();
            }
        }

        return null;
    }
}