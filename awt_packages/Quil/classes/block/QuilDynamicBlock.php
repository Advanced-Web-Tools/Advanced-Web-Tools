<?php

namespace Quil\classes\block;

use DOMDocument;
use model\Model;
use object\ObjectCollection;
use Quil\classes\sources\models\DummySourceModel;

abstract class QuilDynamicBlock
{
    public string $blockName;
    protected DOMDocument $blockDOM;
    protected ObjectCollection $blockData;
    protected ObjectCollection $model;
    public function __construct(string $blockName, string $domSource) {
        $this->blockName = $blockName;
        $this->blockDOM = new DOMDocument();
        $this->blockDOM->loadHTML($domSource);

        $this->blockData = new ObjectCollection();
        $this->blockData->setStrictType(QuilDynamicBlockData::class);
        $this->blockData->setValueOrder(["belongs", "key"]);
        $this->blockData->setSortProperties(["belongs", "key"]);

        $this->model = new ObjectCollection();
        $this->model->setStrictType(Model::class);
        $this->model->setValueOrder(["dummyName"]);
        $this->model->setSortProperties(["dummyName"]);
    }

    public function addModel(Model $model): self {
        $this->model->add($model);
        $this->model->sortByValue();
        return $this;
    }

    public function addData(QuilDynamicBlockData $data): self {
        $this->blockData->add($data);
        $this->blockData->sortByValue();
        return $this;
    }

    public function getValue(string $key) {

    }


    abstract public function render(string|array $args): string;
}