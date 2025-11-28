<?php

namespace model;

use database\DatabaseManager;
use model\interfaces\IRelationBelongs;
use model\interfaces\IRelationHasMany;
use model\interfaces\IRelationWith;
use object\ObjectFactory;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

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
     * Selects a record from the specified table by its ID and populates the object's properties
     * with the data from the selected row.
     *
     * @param int|null $id The ID of the record to be selected. If null, the method returns without performing any operation.
     * @param string $table Optional. The name of the table to query from. Defaults to an inferred table name if not provided.
     * @param string $column Optional. The column name used to match the ID. Defaults to 'id' if not provided.
     *
     * @return void
     */
    final public function selectByID(?int $id, string $table = '', string $column = ''): void
    {
        if ($id === null) return;

        $table = $table ?: $this->inferTableName();
        $column = $column ?: 'id';

        try {
            $result = $this->table($table)->select()->where([$column => $id])->get();

            if (empty($result) || !isset($result[0]) || !is_array($result[0])) {
                return;
            }

            $row = $result[0];

            foreach ($row as $key => $value) {
                $this->{$key} = $value;
            }

            $this->model_source = $table;
            $this->id_column = $column;
            $this->model_id = $id;

            // Relations
            $this->loadWith($row);
            $this->loadBelongsTo($row);
            $this->loadHasMany($row);

        } catch (Throwable $e) {
            if (defined('DEBUG') && DEBUG) {
                die($e->getMessage());
            }
        }
    }

    /**
     * Infers the table name based on the class name of the current object.
     * If the class name is in snake_case, it will be used directly.
     * Otherwise, the class name will be converted from CamelCase to snake_case.
     *
     * @return string The inferred table name in snake_case format.
     */
    public function inferTableName(): string
    {
        $fullClass = get_class($this);
        $exp = explode("\\", $fullClass);
        $shortClass = end($exp);
        return $this->isSnakeCase($shortClass) ? $shortClass : $this->camelToSnake($shortClass);
    }


    /**
     * Loads a related object based on the specified configuration and data row.
     *
     * @param array $row The associative array of data used to load the related object.
     * @return void
     */
    public function loadWith(array $row): void
    {
        if (!($this instanceof IRelationWith)) return;

        $with = $this->with();
        if (empty($with['model']) || empty($with['column'])) return;

        $this->loadRelationObject($with, $row);
    }


    /**
     * Loads a "Belongs To" relationship object for the current model.
     *
     * @param array $row An associative array representing the data row used to load the related object.
     * @return void
     */
    public function loadBelongsTo(array $row): void
    {
        if (!($this instanceof IRelationBelongs)) return;

        $belongs = $this->belongsTo();
        if (empty($belongs['model']) || empty($belongs['column'])) return;

        $this->loadRelationObject($belongs, $row);
    }


    /**
     * Loads and initializes "has many" relationship objects for the current model instance.
     *
     * @param array $row The row of data representing the current model, containing values needed
     *                   to establish relationships.
     * @return void
     */
    public function loadHasMany(array $row): void
    {
        if (!($this instanceof IRelationHasMany)) return;

        $hasMany = $this->hasMany();
        if (empty($hasMany['model']) || empty($hasMany['column'])) return;

        $models = is_array($hasMany['model']) ? $hasMany['model'] : [$hasMany['model']];

        foreach ($models as $model) {
            $model = ltrim($model, '\\');
            if (!class_exists($model)) continue;
            $exp = explode("\\", $model);
            $shortName = end($exp);
            if (!isset($this->{$shortName}) || !is_array($this->{$shortName})) {
                $this->{$shortName} = [];
            }

            $rows = $this->find($hasMany['column'], $this->model_id, $this->camelToSnake($shortName));

            if(isset($hasMany['as']))
                $shortName = $hasMany['as'];

            foreach ($rows as $r) {
                $objFactory = new ObjectFactory();
                $objFactory->setClassName($model);

                if(!isset($hasMany['inConstructor'])) {
                    $objFactory->setMethodCalls(['selectByID']);
                    $objFactory->setMethodArgs(['selectByID' => [$r['id']]]);
                } else {
                    $objFactory->setConstructorArgs([$r['id']]);
                }

                $objFactory->setType(Model::class);
                $this->{$shortName}[] = $objFactory->create();
            }
        }
    }


    /**
     * Loads and initializes a related object based on the provided relation and row data.
     *
     * @param array $relation An associative array defining the relationship, containing details such as the model class and column for foreign key lookup.
     * @param array $row The data row containing information necessary to resolve the relation, typically including the foreign key value.
     * @return void
     */
    protected function loadRelationObject(array $relation, array $row): void
    {
        $modelClass = ltrim($relation['model'], '\\');
        if (!class_exists($modelClass)) return;

        $exp = explode("\\", $modelClass);
        $shortName = end($exp);
        $foreignValue = $row[$relation['column']] ?? null;

        $objFactory = new ObjectFactory();
        $objFactory->setClassName($modelClass);

        if (!isset($relation['inConstructor']) || !$relation['inConstructor']) {
            $objFactory->setMethodCalls(['selectByID']);
            $objFactory->setMethodArgs(['selectByID' => [$foreignValue]]);
        } else {
            $objFactory->setConstructorArgs([$foreignValue]);
        }

        if(isset($relation['as']))
            $shortName = $relation['as'];

        $this->{$shortName} = $objFactory->create();
    }


    /**
     * Creates and returns a related object based on the provided relationship definition and optional row data.
     *
     * @param array $relation An associative array detailing the relationship, including the model class and column for foreign key lookup.
     * @param array $row Optional associative array containing data for resolving the relation, typically with the foreign key value.
     * @return object|null The created related object if successful, or null if the related model class does not exist.
     */
    protected function createRelationObject(array $relation, array $row = []): ?object
    {
        $modelClass = ltrim($relation['model'], '\\');
        if (!class_exists($modelClass)) return null;

        $foreignValue = $row[$relation['column']] ?? ($this->model_id ?? null);

        $factory = new ObjectFactory();
        $factory->setClassName($modelClass);

        if (!isset($relation['inConstructor']) || !$relation['inConstructor']) {
            $factory->setMethodCalls(['selectByID']);
            $factory->setMethodArgs(['selectByID' => [$foreignValue]]);
        } else {
            $factory->setConstructorArgs([$foreignValue]);
        }

        return $factory->create();
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

        if ($this->checkColumn($this->model_id, "updated_on"))
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
    public function saveModel(): int|null
    {
        $save = $this->__toArray();
        foreach ($this->paramBlackList as $key => $value) {
            unset($save[$value]);
        }

        if($this->model_id === null)
            $this->model_source = $this->inferTableName();

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
        if ($this->id_column === null)
            $this->id_column = "id";

        $where = [$this->id_column => $this->model_id];

        if($this->model_source === null)
            $this->model_source = $this->inferTableName();

        return $this->table($this->model_source)->where($where)->delete();
    }


    public function find(string $column, mixed $value, ?string $source = null): array
    {
        $database = new DatabaseManager();

        if ($source === null)
            $source = $this->model_source;

        return $database->table($source)->select()->where([$column => $value])->get();
    }


    /**
     * Adds a given key to the blacklist of parameters.
     *
     * @param string $key The key to be added to the parameter blacklist.
     * @return void
     */
    public function paramBlackList(string $key): void
    {
        $this->paramBlackList[] = $key;
    }


    /**
     * Converts a given string to snake case.
     *
     * @param string $input string for conversion
     * @return string
     */
    protected function camelToSnake(string $input): string
    {
        if ($this->isSnakeCase($input)) {
            return $input;
        }

        $snake = preg_replace('/(?<!^)[A-Z]/', '_$0', $input);
        return strtolower($snake);
    }


    /**
     * Helper function to determine if classname is snake cased
     * @param string $input
     * @return bool
     */
    private function isSnakeCase(string $input): bool
    {
        return (bool)preg_match('/^[a-z0-9]+(?:_[a-z0-9]+)*$/', $input);
    }


    public function fromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
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

        $result = $this->__toArray();

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

        foreach($this->dynamicData as $key => $value) {
            $result[$key] = $value;
        }

        unset($result['dynamicData']);


        return $result;
    }

    /**
     * Retrieves the value of a requested property.
     * Checks for both declared properties and dynamic data.
     *
     * @param string $name Name of the property to retrieve.
     * @return mixed Returns the value of the property if it exists, or null if not found.
     */
    public function &__get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        if (array_key_exists($name, $this->dynamicData)) {
            return $this->dynamicData[$name];
        }

        if ($this instanceof IRelationWith) {
            $with = $this->with();
            $modelClass = $with['model'] ?? null;

            if ($modelClass) {
                $parts = explode('\\', $modelClass);
                $shortName = end($parts);
                $alias = $with['as'] ?? $shortName;

                if ($alias === $name) {
                    $this->dynamicData[$name] = $this->createRelationObject($with);
                    return $this->dynamicData[$name];
                }
            }
        }

        // Initialize null if nothing found
        $this->dynamicData[$name] = null;
        return $this->dynamicData[$name];
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
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
            return;
        }

        $this->dynamicData[$name] = $value;
    }
}