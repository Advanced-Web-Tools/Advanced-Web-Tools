<?php

namespace database\creator;
use database\DatabaseManager;
use database\creator\ColumnCreator;
class TableWizard extends DatabaseManager
{
    private array $columns = [];
    private int $creatorID;

    public function __construct(int $packageId)
    {
        parent::__construct();
        $this->creatorID = $packageId;
    }

    public function addColumn(ColumnCreator $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    public function addColumnToTable(string $tableName, ColumnCreator $column): bool
    {
        if ($this->checkColumn($tableName, $column->name)) {
            return false;
        }

        $sql = "ALTER TABLE `$tableName` ADD COLUMN " . $column->generateColumnSQL() . $column->generateForeignKeySQL();

        $stmt = $this->pdo->prepare($sql);

        var_dump($sql);
        foreach ($this->tables as $table) {
            if($table["name"] == $tableName) {
                $tableID = $table["table_id"];
                break;
            }
        }

        $this->table("awt_table_structure")->insert([
            "table_id" => $tableID,
            "column_name" => $column->name,
            "column_type" => strtolower($column->type),
        ])->executeInsert();

        return $stmt->execute();
    }


    public function createTable(string $tableName): bool
    {
        if ($this->checkTable($tableName)) {
            return false;
        }

        // Separate column definitions and foreign keys
        $columnsSQL = [];
        $foreignKeysSQL = [];

        foreach ($this->columns as $column) {
            $columnsSQL[] = $column->generateColumnSQL(); // Only the column
            if ($foreignKey = $column->generateForeignKeySQL()) {
                $foreignKeysSQL[] = $foreignKey; // Foreign keys separately
            }
        }

        $fullSQLParts = array_merge($columnsSQL, $foreignKeysSQL);
        $columnsSQLString = implode(",\n    ", $fullSQLParts);

        $sql = "CREATE TABLE `$tableName` (\n    $columnsSQLString\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();

        if ($result) {
            $tableID = $this->table("awt_table")->insert([
                "name" => $tableName,
                "creator" => $this->creatorID,
            ])->executeInsert();

            foreach ($this->columns as $column) {
                $this->table("awt_table_structure")->insert([
                    "table_id" => $tableID,
                    "column_name" => $column->name,
                    "column_type" => strtolower($column->type),
                ])->executeInsert();
            }
        }

        $this->__destruct();

        return $result;
    }

    public function __destruct() {
        $this->columns = [];
    }

}