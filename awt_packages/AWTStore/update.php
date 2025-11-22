<?php

use database\creator\ColumnCreator;
use database\creator\TableWizard;
use packages\installer\interface\IPackageUpdate;

final class AWTStoreUpdate implements IPackageUpdate
{


    private TableWizard $dbWizard;
    private ColumnCreator $columnCreator;

    public function update(int $packageId, string $packageName): bool
    {
        /*
         * Stores installed packages from online store
         */

        $this->dbWizard = new TableWizard($packageId);

        $id = ColumnCreator::INT("id", 255)->autoIncrement()->primary();
        $uid = ColumnCreator::VARCHAR("store_uid", 255)->unique();


        $this->dbWizard->addColumn($id)
            ->addColumn($uid)->createTable("store_packages");

        return true;
    }
}