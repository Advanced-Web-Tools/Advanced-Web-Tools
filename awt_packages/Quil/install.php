<?php

use database\creator\ColumnCreator;
use database\creator\TableWizard;
use packages\installer\interface\IPackageInstall;

class QuilInstall implements IPackageInstall
{

    private TableWizard $dbWizard;
    private ColumnCreator $columnCreator;

    public function postInstall(int $packageID, string $packageName): bool
    {
        if (!$this->createQuilRoutes($packageID))
            return false;

        if (!$this->createQuilPage($packageID))
            return false;

        if (!$this->createQuilContents($packageID))
            return false;

        if (!$this->createDataSource($packageID))
            return false;

        return true;
    }

    private function createID(): ColumnCreator
    {
        $qp_id = new ColumnCreator();
        $qp_id->INT("id", 255)
            ->primary()
            ->autoIncrement()
            ->unique();

        return $qp_id;
    }

    private function createCreatedBy(): ColumnCreator
    {
        $qp_creator = new ColumnCreator();
        $qp_creator->INT("created_by", 255)->index()
            ->nullable()
            ->foreignKey("awt_admin", "id", "SET NULL", "CASCADE");
        return $qp_creator;
    }

    private function createCreatedAt(): ColumnCreator
    {
        $qp_creation_date = new ColumnCreator();
        $qp_creation_date->DATE("creation_date")->nullable();
        return $qp_creation_date;
    }

    private function createPageId(): ColumnCreator
    {
        $qp_page_id = new ColumnCreator();
        return $qp_page_id->INT("page_id", 255)->index()->unique()->foreignKey("quil_page", "id", "CASCADE", "CASCADE");
    }

    private function createQuilPage(int $packageID): bool
    {
        $qp_route_id = new ColumnCreator();
        $qp_name = new ColumnCreator();
        $qp_description = new ColumnCreator();

        $qp_update_date = new ColumnCreator();

        $quil_page = new TableWizard($packageID);

        $qp_id = $this->createID();

        $qp_creator = $this->createCreatedBy();

        $qp_creation_date = $this->createCreatedAt();

        $qp_route_id = $qp_route_id->INT("route_id", 255)
            ->index()
            ->nullable()
            ->foreignKey("quil_page_route", "id", "SET NULL", "CASCADE");

        $qp_name = $qp_name->VARCHAR("name", 255);

        $qp_description = $qp_description->VARCHAR("description", 255)->nullable();

        $qp_update_date = $qp_update_date->DATE("last_update")->nullable();

        $quil_page->addColumn($qp_id);
        $quil_page->addColumn($qp_creator);
        $quil_page->addColumn($qp_route_id);
        $quil_page->addColumn($qp_name);
        $quil_page->addColumn($qp_description);
        $quil_page->addColumn($qp_creation_date);
        $quil_page->addColumn($qp_update_date);

        return $quil_page->createTable("quil_page");
    }

    private function createQuilRoutes(int $packageID): bool
    {
        $qp_id = $this->createID();
        $created_by = $this->createCreatedBy();

        $route = new ColumnCreator();

        $route = $route->VARCHAR("route", 255)->index()->unique();

        $qp_route = new TableWizard($packageID);
        $qp_route->addColumn($qp_id);
        $qp_route->addColumn($route);
        $qp_route->addColumn($created_by);

        return $qp_route->createTable("quil_page_route");
    }

    private function createQuilContents(int $packageID): bool
    {
        $qp_id = $this->createID();
        $qp_page_id = $this->createPageId();

        $qp_content = new ColumnCreator();
        $qp_content = $qp_content->LONGTEXT("content")->nullable()->default('NULL');

        $quil_content = new TableWizard($packageID);
        $quil_content->addColumn($qp_id);
        $quil_content->addColumn($qp_page_id);
        $quil_content->addColumn($qp_content);

        return $quil_content->createTable("quil_page_content");
    }

    private function createDataSource(int $packageID): bool
    {
        $qp_id = $this->createID();
        $qp_page_id = $this->createPageId();

        $qp_table_id = new ColumnCreator();

        $qp_column_selector = new ColumnCreator();

        $qp_bind_param_url = new ColumnCreator();

        $qp_default_param_value = new ColumnCreator();

        $qp_source_name = new ColumnCreator();

        $qp_table_id = $qp_table_id->INT("table_id", 255)->index()->foreignKey("awt_table", "id", "CASCADE", "CASCADE");

        $qp_column_selector = $qp_column_selector->VARCHAR("column_selector", 255);
        $qp_bind_param_url = $qp_bind_param_url->VARCHAR("bind_param_url", 255);
        $qp_default_param_value = $qp_default_param_value->VARCHAR("default_param_value", 255)->nullable();
        $qp_source_name = $qp_source_name->VARCHAR("source_name", 255);

        $data_source = new TableWizard($packageID);
        $data_source->addColumn($qp_id);
        $data_source->addColumn($qp_page_id);
        $data_source->addColumn($qp_table_id);
        $data_source->addColumn($qp_column_selector);
        $data_source->addColumn($qp_bind_param_url);
        $data_source->addColumn($qp_default_param_value);
        $data_source->addColumn($qp_source_name);

        return $data_source->createTable("quil_page_data_source");
    }

}