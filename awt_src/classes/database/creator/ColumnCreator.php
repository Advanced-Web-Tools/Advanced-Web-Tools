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
    private bool $defaultAsDefined = false; // New flag for raw SQL defaults

    public function INT(string $name, string $length): self
    {
        $this->type = 'INT';
        $this->name = $name;
        $this->length = $length;
        return $this;
    }

    public function VARCHAR(string $name, string $length): self
    {
        $this->type = 'VARCHAR';
        $this->name = $name;
        $this->length = $length;
        return $this;
    }

    public function TEXT(string $name): self
    {
        $this->type = 'TEXT';
        $this->name = $name;
        return $this;
    }

    public function LONGTEXT(string $name): self
    {
        $this->type = 'LONGTEXT';
        $this->name = $name;
        return $this;
    }

    public function DATE(string $name): self
    {
        $this->type = 'DATE';
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the default value for the column.
     * Use 'AS_DEFINED' for raw SQL defaults like 'CURRENT_TIMESTAMP'.
     */
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

    public function generateSQL(): string
    {
        $sql = "`" . $this->name . "` {$this->type}";

        if (!empty($this->length)) {
            $sql .= "({$this->length})";
        }

        $sql .= $this->nullable ? " NULL" : " NOT NULL";

        if (!empty($this->default)) {
            if ($this->defaultAsDefined) {
                $sql .= " DEFAULT {$this->default}"; // Raw SQL, e.g., CURRENT_TIMESTAMP
            } else {
                $sql .= " DEFAULT '{$this->default}'"; // Escaped default
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

        if ($this->foreignKeyReferenceTable && $this->foreignKeyReferenceColumn) {
            $sql .= ", FOREIGN KEY (`{$this->name}`) REFERENCES `{$this->foreignKeyReferenceTable}`(`{$this->foreignKeyReferenceColumn}`)";

            if ($this->onDelete) {
                $sql .= " ON DELETE {$this->onDelete}";
            }
            if ($this->onUpdate) {
                $sql .= " ON UPDATE {$this->onUpdate}";
            }
        }

        return $sql;
    }
}
