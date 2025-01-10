<?php

namespace data\models;

use model\Model;

class DataModel extends Model
{
    public ?int $id;
    public ?string $file_location = null;
    public ?string $dataName = null;
    public ?string $dataType = null;
    public ?int $ownerId = null;
    public ?string $ownerName = null;
    public ?string $ownerType = null;

    public function __construct(?int $id = null)
    {
        parent::__construct();

        if ($id !== null) {
            $this->selectByID($id, "awt_data");
            $this->buildLocation();
        }
    }

    public function buildFromArray(array $array): self
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }

        $this->buildLocation();
        return $this;
    }

    private function buildLocation(bool $local = false): self
    {

        $workDir = "";
        $subDir = "";

        if ($this->ownerType === "System") {
            $workDir = "awt_data/";
        }

        if ($this->ownerType === "User") {
            $workDir = "awt_data/media/uploads/";
        }

        if ($this->ownerType === "Package") {
            $workDir = "awt_data/media/packages/";
            $subDir = $this->ownerName . "/";
        }

        if ($local)
            $workdir = "/" . $workDir;

        $workDir .= $this->dataType . "/" . $subDir . $this->dataName;

        $this->setFileLocation($workDir);


        return $this;
    }


    public function setFileLocation(string $location): self
    {
        $this->file_location = $location;
        return $this;
    }


    public function getLocation(bool $local = false): string
    {
        $this->buildLocation($local);
        return $this->file_location;
    }

    public function deleteData(): bool
    {
        $result = $this->deleteModel();
        if (!$result)
            return false;

        $this->buildLocation(true);
        if ($this->file_location == null)
            return false;

        $res = unlink($this->file_location);

        if (!$res)
            return false;

        return true;
    }
}