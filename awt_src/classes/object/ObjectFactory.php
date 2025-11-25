<?php
namespace object;
use ReflectionClass;
use ReflectionException;

/**
 * A factory class used to dynamically create objects and configure them
 * with specified properties, methods, and constructor arguments before returning.
 */
class ObjectFactory
{
    private ?string $classPath = null;
    private ?string $className = null;
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

    /**
     * Sets the method calls array.
     * @param array $methodCalls
     * @return self
     */
    public function setMethodCalls(array $methodCalls): self
    {
        $this->methodCalls = $methodCalls;
        return $this;
    }

    /**
     * Sets the method arguments.
     * @param array $methodArgs
     * @return self
     */
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
     * Adds a method call to the internal list.
     * @param string $name The name of the method to add.
     * @return self
     */
    public function addMethodCall(string $name): self {
        $this->methodCalls[] = $name;
        return $this;
    }

    /**
     * Adds arguments for a specific method.
     * @param string $method The name of the method.
     * @param array $args The arguments to associate with the method.
     * @return self
     */
    public function addMethodArgs(string $method, array $args): self {
        $this->methodArgs[$method] = $args;
        return $this;
    }

    /**
     * Sets the class name for the current instance.
     * @param string $class The name of the class to set.
     * @return self
     */
    public function setClassName(string $class): self
    {
        $this->className = $class;
        return $this;
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
     * Creates and returns an instance of a class based on the provided class name, file path, or type.
     * Attempts to dynamically load and instantiate a class from the specified path or directly by name.
     * Throws exceptions or returns null in cases where class loading or instantiation fails, depending on the debug mode.
     *
     * @return object|null The created object instance or null if instantiation fails.
     */
    public function create(): ?object
    {
        $classToInstantiate = null;

        if ($this->className !== null) {
            if ($this->classPath !== null && !class_exists($this->className)) {
                include_once $this->classPath;
            }
            $classToInstantiate = $this->className;
        } elseif ($this->classPath !== null) {
            $beforeClasses = get_declared_classes();
            include_once $this->classPath;
            $afterClasses = get_declared_classes();
            $newClasses = array_diff($afterClasses, $beforeClasses);

            if (empty($newClasses)) {
                if (DEBUG) throw new \Exception("No new class was declared in the file: {$this->classPath}");
                return null;
            }
            
            foreach ($newClasses as $newClass) {
                try {
                    $reflection = new ReflectionClass($newClass);
                    if (!$reflection->isAbstract()) {
                        $classToInstantiate = $newClass;
                        break;
                    }
                } catch (ReflectionException $e) {
                    if (DEBUG) throw new \Exception("Reflection failed for class {$newClass}.", 0, $e);
                    return null;
                }
            }
        }

        if ($classToInstantiate === null) {
            if (DEBUG) throw new \Exception("Could not determine class to instantiate from the provided path or name.");
            return null;
        }

        try {
            $reflection = new ReflectionClass($classToInstantiate);
        } catch (ReflectionException $e) {
            if (DEBUG) throw new \Exception("Class '{$classToInstantiate}' not found or could not be reflected.", 0, $e);
            return null;
        }

        if ($reflection->isAbstract()) {
            if (DEBUG) throw new \Exception("Cannot create an instance of abstract class {$classToInstantiate}.");
            return null;
        }

        $object = (count($this->constructorArgs) > 0)
            ? $reflection->newInstanceArgs($this->constructorArgs)
            : $reflection->newInstance();

        if ($this->type !== null && !$this->checkType($object, $this->type)) {
            if (DEBUG) throw new \Exception("Object of class " . get_class($object) . " is not an instance of expected type {$this->type}");
            return null;
        }

        $object = $this->setProperty($object);
        return $this->callMethods($object);
    }
}