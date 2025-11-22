<?php

namespace database\creator;

class ColumnCreator
{
    public string $type = '';
    public string $name = '';
    public string $default = '';
    public bool $autoIncrement = false;
    public string $length = '';
    public bool $nullable = false;
    public string $index = '';
    public ?string $foreignKeyReferenceTable = null;
    public ?string $foreignKeyReferenceColumn = null;
    public ?string $onDelete = null;
    public ?string $onUpdate = null;
    private bool $defaultAsDefined = false;

    public static function INT(string $name, string $length): self
    {
        $instance = new self();
        $instance->type = 'INT';
        $instance->name = $name;
        $instance->length = $length;
        return $instance;
    }

    public static function VARCHAR(string $name, string $length): self
    {
        $instance = new self();
        $instance->type = 'VARCHAR';
        $instance->name = $name;
        $instance->length = $length;
        return $instance;
    }

    public static function TEXT(string $name): self
    {
        $instance = new self();
        $instance->type = 'TEXT';
        $instance->name = $name;
        return $instance;
    }

    public static function LONGTEXT(string $name): self
    {
        $instance = new self();
        $instance->type = 'LONGTEXT';
        $instance->name = $name;
        return $instance;
    }

    public static function DATE(string $name): self
    {
        $instance = new self();
        $instance->type = 'DATE';
        $instance->name = $name;
        return $instance;
    }

    public static function TIMESTAMP(string $name): self
    {
        $instance = new self();
        $instance->type = 'TIMESTAMP';
        $instance->name = $name;
        return $instance;
    }

    public function default(string $value, bool $asDefined = false): self
    {
        $this->default = $value;
        $this->defaultAsDefined = $asDefined;
        return $this;
    }

    public function autoIncrement(): self
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function nullable(): self
    {
        $this->nullable = true;
        return $this;
    }

    public function primary(): self
    {
        $this->index = "PRIMARY";
        return $this;
    }

    public function unique(): self
    {
        $this->index = "UNIQUE";
        return $this;
    }

    public function index(): self
    {
        $this->index = "INDEX";
        return $this;
    }

    public function foreignKey(string $referenceTable, string $referenceColumn, ?string $onDelete = null, ?string $onUpdate = null): self
    {
        $this->foreignKeyReferenceTable = $referenceTable;
        $this->foreignKeyReferenceColumn = $referenceColumn;
        $this->onDelete = $onDelete;
        $this->onUpdate = $onUpdate;
        return $this;
    }

    public function generateColumnSQL(): string
    {
        $sql = "`" . $this->name . "` {$this->type}";

        if (!empty($this->length)) {
            $sql .= "({$this->length})";
        }

        $sql .= $this->nullable ? " NULL" : " NOT NULL";

        if (!empty($this->default)) {
            $nonQuotedDefaults = ['CURRENT_TIMESTAMP', 'NULL', 'TRUE', 'FALSE', 'NOW()'];
            if ($this->defaultAsDefined || in_array(strtoupper($this->default), $nonQuotedDefaults, true)) {
                $sql .= " DEFAULT {$this->default}";
            } else {
                $sql .= " DEFAULT '{$this->default}'";
            }
        }

        if ($this->autoIncrement) {
            $sql .= " AUTO_INCREMENT";
        }

        if ($this->index === "PRIMARY") {
            $sql .= " PRIMARY KEY";
        } elseif ($this->index === "UNIQUE") {
            $sql .= " UNIQUE";
        }

        return $sql;
    }

    public function generateForeignKeySQL(): ?string
    {
        if ($this->foreignKeyReferenceTable && $this->foreignKeyReferenceColumn) {
            $sql = "FOREIGN KEY (`{$this->name}`) REFERENCES `{$this->foreignKeyReferenceTable}`(`{$this->foreignKeyReferenceColumn}`)";

            if ($this->onDelete) {
                $sql .= " ON DELETE {$this->onDelete}";
            }
            if ($this->onUpdate) {
                $sql .= " ON UPDATE {$this->onUpdate}";
            }

            return $sql;
        }

        return null;
    }
}
