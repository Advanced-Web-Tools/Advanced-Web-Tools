<?php

use database\creator\ColumnCreator;
use database\creator\TableWizard;
use packages\installer\interface\IPackageInstall;

class PackageInstall implements IPackageInstall
{
    public function postInstall(int $packageID, string $packageName): bool
    {
        $wizard = new TableWizard($packageID);

        $columnCreator = new ColumnCreator();
        $id_column = $columnCreator->INT("id", "255")->primary()->autoIncrement();
        $name_column = $columnCreator->VARCHAR("name", "255")->default("Missing name");

        $wizard->addColumn($id_column)->addColumn($name_column)->createTable("test_table");

        return true;
    }
}