<?php

namespace object;

use http\Exception;
use ReflectionClass;
use ReflectionException;

class ObjectFactory
{
    private string $classPath;
    public ?string $type = null;
    private array $constructorArgs = [];
    private array $methodCalls = [];
    private array $methodArgs = [];
    private array $properties = [];

    public function __construct()
    {

    }

    /**
     * Sets a path to the class which will be initialized.
     *
     * @param string $classPath
     * @return self
     * @throws \Exception if file does not exist.
     */
    public function setClassPath(string $classPath): self
    {
        if (!file_exists($classPath))
            throw new \Exception("File $classPath does not exist");


        $this->classPath = $classPath;
        return $this;
    }

    /**
     * Sets type of the given class for stricter checking.
     *
     *
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }


    /**
     * Sets constructor arguments which will be passed on object creation.
     *
     * Array must be in format: ["arg1", "arg2"...]
     *
     *
     * @param array $constructorArgs
     * @return self
     */
    public function setConstructorArgs(array $constructorArgs): self
    {
        $this->constructorArgs = $constructorArgs;
        return $this;
    }


    /**
     * Sets $this->properties from given array.
     *
     * Array must be in format ["key" => "value"].
     * Where key is property name.
     *
     * @param array $properties
     * @return $this
     */
    public function setProperties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }


    /**
     * Dynamically adds property to the properties
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addProperty(string $name, string $value): self
    {
        $this->properties[$name] = $value;
        return $this;
    }

    public function setMethodCalls(array $methodCalls): self
    {
        $this->methodCalls = $methodCalls;
        return $this;
    }

    public function setMethodArgs(array $methodArgs): self
    {
        $this->methodArgs = $methodArgs;
        return $this;
    }


    /**
     * Sets values from $properties to the object.
     * @param object $object
     * @return self
     * @throws \Exception if DEBUG = true, on non-existing properties in the class.
     */
    protected function setProperty(object $object): object
    {
        if (count($this->properties) === 0)
            return $object;

        foreach ($this->properties as $property => $value) {
            if (property_exists($object, $property)) {
                $object->{$property} = $value;
            } else {
                if (DEBUG)
                    throw new \Exception(get_class($object) . '::$' . $property . ' does not exist in ' . $this->classPath);
            }
        }

        return $object;
    }

    /**
     * Checks if passed object matches the given type.
     * @param object $object
     * @param string $type
     * @return bool
     */
    public function checkType(object $object, string $type): bool
    {
        if ($object instanceof $type)
            return true;

        return false;
    }

    /**
     *
     * Calls a method from `methodCalls` array.
     * If same method exists in `methodArgs` args will be passed in order they were set.
     *
     * Array: $methodCalls = ['method1', 'method2']
     * Array: $methodArgs = ['method1' => ['arg1', true, 'arg2']]
     * Result: $object->{$methodCall}($arg1, $arg2, $arg3), $object->{$methodCall}()
     *
     * @param object $object
     * @return self
     * @throws \Exception if DEBUG = true, on non-existing method call.
     */
    protected function callMethods(object $object): object
    {
        if (count($this->methodCalls) === 0)
            return $object;

        foreach ($this->methodCalls as $methodCall) {

            if (method_exists($object, $methodCall)) {

                if (isset($this->methodArgs[$methodCall])) {

                    $args = $this->methodArgs[$methodCall];

                    if (!is_array($args)) {
                        $args = [$args];
                    }

                    $object->{$methodCall}(...$args);

                } else {
                    $object->{$methodCall}();
                }

            } else {
                if (DEBUG)
                    throw new \Exception(get_class($object) . '::' . $methodCall . ' does not exist in ' . $this->classPath);
            }
        }

        return $object;
    }


    /**
     * Initializes and returns an object with previous parameters set.
     * @return object|null
     * @throws ReflectionException
     * @throws \Exception
     */
    public function create(): object|null
    {
        $beforeClasses = get_declared_classes();

        include_once $this->classPath;

        $afterClasses = get_declared_classes();

        $newClasses = array_diff($afterClasses, $beforeClasses);

        foreach ($newClasses as $className) {

            try {
                $reflection = new ReflectionClass($className);
            } catch (\Exception $e) {
                if (DEBUG)
                    throw new \Exception("{$className} failed to create object.");
                exit();
            }


            if (!$reflection->isAbstract()) {

                if (count($this->constructorArgs) > 0) {
                    $object = $reflection->newInstanceArgs($this->constructorArgs);
                } else {
                    $object = $reflection->newInstance();
                }

                if ($this->type !== null) {
                    $res = $this->checkType($object, $this->type);

                    if (!$res)
                        if (DEBUG)
                            throw new \Exception("{$className} was an instance of " . get_class($object) . ". Expected: {$this->type}");
                }

                $object = $this->setProperty($object);

                return $this->callMethods($object);

            } else {

                if (DEBUG)
                    throw new \Exception("$className failed to create object. $className is an abstract class.");

            }
        }


        return null;
    }


}