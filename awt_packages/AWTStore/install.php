<?php

use database\creator\ColumnCreator;
use database\creator\TableWizard;
use packages\installer\interface\IPackageInstall;

class AWTStoreInstall implements IPackageInstall
{
    private TableWizard $dbWizard;
    private ColumnCreator $columnCreator;

    public function postInstall(int $packageID, string $packageName): bool
    {

        $this->dbWizard = new TableWizard($packageID);

        $id = ColumnCreator::INT("id", 255)->autoIncrement()->primary();
        $uid = ColumnCreator::VARCHAR("store_uid", 255)->unique();


        $this->dbWizard->addColumn($id)
        ->addColumn($uid)->createTable("store_packages");

        return true;
    }
}