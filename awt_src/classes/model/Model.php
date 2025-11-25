<?php

namespace model;

use database\DatabaseManager;
use ReflectionClass;
use ReflectionProperty;

/**
 * Model Class
 *
 * This abstract class extends the DatabaseManager to provide common
 * functionality for all models, including methods for retrieving
 * records from the database by ID or retrieving all records from a table.
 * It serves as a base class for specific models that represent database
 * entities.
 */
abstract class Model extends DatabaseManager
{

    public ?string $model_source = null;
    protected ?int $model_id = null;
    public ?string $id_column = null;
    public array $dynamicData = [];

    protected array $paramBlackList = ["tables", "model_source", "id_column"];

    /**
     * Initializes the Model by calling the parent constructor of
     * DatabaseManager.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves a single record from the specified table by its ID.
     * If no table is provided, it infers the table name from the
     * class name. If no column is specified, it defaults to "id".
     * The method populates the model's properties with the
     * retrieved data.
     *
     * @param int $id The ID of the record to retrieve.
     * @param string $table The name of the table to select from.
     * @param string $column The name of the column to use for
     * identifying the record (defaults to "id").
     */
    final protected function selectByID(int $id, string $table = '', string $column = ''): void
    {
        if ($table == '') {
            $table = explode("\\", self::class);
            $table = end($table);
        }

        if ($column === '') {
            $column = "id";
        }

        $result = $this->table($table)->select()->where([$column => $id])->get();

        foreach ($result[0] as $key => $value) {
            $this->{$key} = $value;
        }

        $this->model_source = $table;
        $this->id_column = $column;
        $this->model_id = $id;
    }

    /**
     * Retrieves all records from the specified table. If no table
     * is provided, it infers the table name from the class name.
     * This method does not populate any properties, as it is
     * intended for fetching data without direct assignment.
     *
     * @param string $table The name of the table to select from.
     * @return array Returns the array of query result;
     */
    final protected function selectAll(string $table = ''): array
    {
        if ($table == '') {
            $table = explode("\\", self::class);
            $table = end($table);
        }

        return $this->table($table)->select()->where(['1' => '1'])->get();
    }

    /**
     * Returns the value of the specified property if it exists in
     * the model; otherwise, it returns null. This method is useful
     * for accessing model properties dynamically.
     *
     * @param string $key The name of the property to retrieve.
     * @return mixed The value of the property or null if it does not exist.
     */
    final public function getParam(string $key): mixed
    {
        return $this->{$key} ?? null;
    }


    public function setModelId(int $id): void
    {
        $this->model_id = $id;
    }


    /**
     * Saves the current state of the model to the database by updating the record
     * associated with the model's identifier column.
     *
     * The method converts the model's properties into an array, applies necessary
     * transformations (such as setting an updated timestamp, if applicable), and
     * removes blacklisted parameters from the update data. It then performs an
     * update operation on the corresponding database table.
     *
     * @return bool True if the update operation was successful, false otherwise.
     */

    public function save(): bool
    {

        $where = [$this->id_column => $this->model_id];

        $update = $this->__toArray();

        if($this->checkColumn($this->model_id, "updated_on"))
            $update[] = ["updated_on" => 'DEFAULT'];

        foreach ($this->paramBlackList as $key => $value) {
            unset($update[$value]);
        }

        return $this->table($this->model_source)->where($where)->update($update);
    }

    /**
     * Saves the current model to the database by converting it to an array,
     * removing blacklisted parameters, and inserting it into the specified table.
     *
     * @return bool True if the model was successfully saved, false otherwise.
     */
    public function saveModel(): bool
    {
        $save = $this->__toArray();
        foreach ($this->paramBlackList as $key => $value) {
            unset($save[$value]);
        }

        return $this->table($this->model_source)->insert($save)->executeInsert();
    }

    /**
     * Deletes the current model from the data source (database) based on the defined identifier column and its value.
     * The identifier column is determined by $id_column, defaulting to "id" if not set.
     *
     * @return bool True if the model was successfully deleted, false otherwise.
     */
    public function deleteModel(): bool
    {
        if($this->id_column === null)
            $this->id_column = "id";

        $where = [$this->id_column => $this->model_id];
        return $this->table($this->model_source)->where($where)->delete();
    }


    /**
     * Adds a given key to the blacklist of parameters.
     *
     * @param string $key The key to be added to the parameter blacklist.
     * @return void
     */
    public function paramBlackList(string $key): void {
        $this->paramBlackList[] = $key;
    }


    /**
     * Generates a string representation of the object by creating a JSON-encoded
     * string of its public properties and their values.
     *
     * @return string A JSON-formatted string representing the public properties of the object.
     */
    public function __toString(): string
    {
        $reflect = new ReflectionClass($this);
        $vars = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $result = [];

        foreach ($vars as $property) {
            $result[$property->getName()] = $property->getValue($this);
        }

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Converts the object's public properties into an associative array.
     * Only includes properties that are publicly accessible.
     *
     * @return array Returns an associative array of the object's public properties.
     */
    public function __toArray(): array
    {
        $reflect = new ReflectionClass($this);
        $vars = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $result = [];

        foreach ($vars as $property) {
            if ($property->isPublic()) {
                $result[$property->getName()] = $property->getValue($this);
            }
        }

        return $result;
    }

    /**
     * Retrieves the value of a requested property.
     * Checks for both declared properties and dynamic data.
     *
     * @param string $name Name of the property to retrieve.
     * @return mixed Returns the value of the property if it exists, or null if not found.
     */
    public function __get(string $name): mixed
    {
        if(property_exists($this, $name))
            return $this->{$name};

        if(array_key_exists($name, $this->dynamicData))
            return $this->dynamicData[$name];

        return null;
    }


    /**
     * Dynamically sets a value to a property.
     * Updates an existing property if it exists, otherwise adds it to dynamic data.
     *
     * @param string $name The name of the property to set.
     * @param mixed $value The value to assign to the property.
     *
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        if(property_exists($this, $name)) {
            $this->{$name} = $value;
            return;
        }

        $this->dynamicData[$name] = $value;
    }
}