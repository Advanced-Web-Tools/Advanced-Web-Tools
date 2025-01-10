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

        $sql = "ALTER TABLE `$tableName` ADD COLUMN " . $column->generateSQL();
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute();
    }


    public function createTable(string $tableName): bool
    {
        if ($this->checkTable($tableName)) {
            return false;
        }

////        $column = new ColumnCreator();
////        $date = $column->DATE("created_on")->default("CURRENT_TIMESTAMP");
////        $update = $column->DATE("updated_on")->default("CURRENT_TIMESTAMP");
////
////        $this->addColumn($date);
////        $this->addColumn($update);

        $columnsSQL = array_map(fn($column) => $column->generateSQL(), $this->columns);
        $columnsSQLString = implode(", ", $columnsSQL);

        $sql = "CREATE TABLE `$tableName` ($columnsSQLString)";
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

        return $result;
    }

}