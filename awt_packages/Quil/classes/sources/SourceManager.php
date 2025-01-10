<?php

namespace Quil\classes\sources;

use database\DatabaseManager;
use Quil\classes\sources\models\SourceModel;

class SourceManager
{
    private DatabaseManager $database;
    private array $sources;
    private int $pageId;

    public array $models;

    public function __construct(int $pageId)
    {
        $this->database = new DatabaseManager();
        $this->sources = [];
        $this->pageId = $pageId;
    }

    public function getTables(): array
    {
        $tables = $this->database->getTables()->tables;
        $groupedTables = array_reduce($tables, function ($carry, $item) {
            if (!isset($carry[$item['table_id']])) {
                $carry[$item['table_id']] = [
                    'name' => $item['name'],
                    'table_id' => $item['table_id'],
                    'columns' => []
                ];
            }

            $carry[$item['table_id']]['columns'][] = [
                'column_name' => $item['column_name'],
                'column_type' => $item['column_type']
            ];

            return $carry;
        }, []);

        return array_values($groupedTables);
    }

    public function fetchSources(): self
    {
        $result = $this->database->table('quil_page_data_source')
            ->select(['*'])->where(['page_id' => $this->pageId])->get();

        foreach ($result as $source) {
            $this->sources[$source['source_name']] = new SourceModel($source['id']);
        }

        return $this;
    }

    public function getSources(): array
    {
        return $this->sources;
    }

    public function addSource(int $table_id, string $column, string $url_param, string $default, string $name): ?int
    {
        return $this->database->table('quil_page_data_source')
            ->insert([
                'page_id' => $this->pageId,
                'table_id' => $table_id,
                'column_selector' => $column,
                'bind_param_url' => $url_param,
                'default_param_value' => $default,
                'source_name' => $name,
            ])->executeInsert();
    }

    public function updateSource(int $id, int $table_id, string $column, string $url_param, string $default, string $name): ?bool
    {
        return $this->database->table('quil_page_data_source')->where(["id" => $id])->update(
            [
                'page_id' => $this->pageId,
                'table_id' => $table_id,
                'column_selector' => $column,
                'bind_param_url' => $url_param,
                'default_param_value' => $default,
                'source_name' => $name
            ]
        );
    }

    public function deleteSource($id): bool
    {
        return $this->database->table('quil_page_data_source')->where(["id" => $id])->delete();
    }

    public function removeSource(int $id): bool
    {
        return $this->database->table('quil_page_data_source')->where(['id' => $id])->delete();
    }

}