<?php

namespace setting\model;

use model\Model;

class SettingModel extends Model
{
    public int $id;
    public int $package_id;
    public string $package_name;
    public string $name;
    public mixed $value;
    public string $type;
    public string $category;
    public string $constName;

    public function __construct(int $id, string $package_name)
    {
        parent::__construct();

        $this->id = $id;
        $this->selectByID($this->id, "awt_setting");
        $this->type = $this->getParam("value_type");
        $this->package_name = $package_name;
    }

    private function convertType(): void
    {
        $this->type = match($this->type) {
            "text" => "string",
            "number" => "int",
            "boolean" => "bool",
            default => "string",
        };
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        $this->__destruct();

        $this->table("awt_setting")->where("id", $this->id)->update(["value" => $value]);

        return $this;
    }

    public function createObjectConstant(): self
    {
        $this->constName = strtoupper(str_replace(" ", "_", strtoupper($this->package_name)) ."_". str_replace(" ", "_", strtoupper($this->name)));

        if(!defined($this->constName)) {
            define("SETT_" . $this->constName, $this);
        }

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

}