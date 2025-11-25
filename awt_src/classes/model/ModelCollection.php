<?php

namespace model;

use database\DatabaseManager;
use model\interfaces\IRelationHasMany;
use object\ObjectCollection;

abstract class ModelCollection extends DatabaseManager
{
    private string $model;
    public ObjectCollection $obCollection;

    public function __construct(string $table = "")
    {
        parent::__construct();

        if ($table === "") {
            $this->model = $this->getModel();
            $table = $this->getTable();
        }

        $results = $this->table($table)->select()->where(["1" => "1"])->get();

        $this->obCollection = new ObjectCollection();
        $this->obCollection->setKey("id")->setStrictType(Model::class);

        foreach ($results as $result) {
            $model = $this->createModel($result);
            $model->model_source = $table;
            $this->obCollection->add($model);
        }

        $this->obCollection->sortByKey();

    }

    abstract public function getModel(): string;

    protected function createModel(?array $data = null): Model
    {
        $model = new $this->model(null);
        if(!($model instanceof Model)) {

            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);

            foreach ($trace as $level) {
                echo "File: " . ($level['file'] ?? '[internal]') . " Line: " . ($level['line'] ?? '?') . "<br>";
                echo "Function: " . ($level['function'] ?? '[global]') . "<br><br>";
            }

            die("ModelCollection: Model must be a subclass of Model.");

        }

        if($data === null)
            return $model;


        $model->fromArray($data);

        $model->setModelId($data["id"]);

        $model->id_column = "id";

        $model->loadWith($data);
        $model->loadBelongsTo($data);
        $model->loadHasMany($data);

        return $model;
    }

    protected function getTable(): string
    {
        $model = $this->createModel();

        return $model->inferTableName();
    }
}