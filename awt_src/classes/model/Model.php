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

    protected string $model_source;
    protected ?int $model_id = null;
    protected string $id_column;

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


        parent::__destruct();
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

        $result = $this->table($table)->select()->where(['1' => '1'])->get();
        parent::__destruct();
        return $result;
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

    /**
     * Saves the model to database
     * @return bool true on success otherwise false
     */
    final public function save(): bool
    {

        $where = [$this->id_column => $this->model_id];

        $update = $this->__toArray();

        if($this->checkColumn($this->model_id, "updated_on"))
            $update[] = ["updated_on" => 'DEFAULT'];


        return $this->table($this->model_source)->where($where)->update($update);
    }

    /**
     *  Creates a json of properties.
     *  Works only on public properties.
     *
     * @return string Returns a json string of an object.
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
     * Creates an array of a current object
     * @return array
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
}