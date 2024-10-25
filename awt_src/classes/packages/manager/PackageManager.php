<?php

namespace packages\manager;

use data\DataManager;
use database\DatabaseManager;
use packages\enums\EPackageStatus;
use packages\enums\EPackageType;
use packages\Package;
use packages\runtime\Runtime;

/**
 * Class Manager
 *
 * The Manager class is responsible for managing a collection of packages in a runtime environment.
 * It handles the addition, retrieval, enabling, and disabling of packages. By interacting with a
 * DatabaseManager, it fetches package data from a database and categorizes packages into active
 * and disabled states based on their status.
 */
class PackageManager
{
    private DatabaseManager $databaseManager;

    protected array $packages = [];
    protected array $active = [];
    protected array $disabled = [];

    public function __construct()
    {
        $this->databaseManager = new DatabaseManager();
    }

    /**
     * Adds a package to the internal packages array.
     *
     * @param Package $package The package to be added.
     */
    public function addPackage(Package $package): void
    {
        $this->packages[$package->getId()] = $package;
    }

    /**
     * Retrieves all packages.
     *
     * @return array An array of all added packages.
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * Retrieves all active packages.
     *
     * @return array An array of active packages.
     */
    public function getActive(): array
    {
        return $this->active;
    }

    /**
     * Retrieves all disabled packages.
     *
     * @return array An array of disabled packages.
     */
    public function getDisabled(): array
    {
        return $this->disabled;
    }

    /**
     * Disables a package by updating its status in the database.
     *
     * @param int $id The ID of the package to be disabled.
     */
    public function disablePackage(int $id): void
    {
        $this->databaseManager->table("awt_package")->where(["id" => $id])->update(["status" => 0]);
    }

    /**
     * Enables a package by updating its status in the database.
     *
     * @param int $id The ID of the package to be enabled.
     */
    public function enablePackage(int $id): void
    {
        $this->databaseManager->table("awt_package")->where(["id" => $id])->update(["status" => 1]);
    }

    /**
     * Fetches packages from the database and populates the internal packages array.
     *
     * This method retrieves package data, initializes Runtime objects,
     * and categorizes them as active or disabled based on their status.
     */
    public function fetchPackages(): void
    {

        $this->packages = $this->databaseManager
            ->table("awt_package")
            ->select()
            ->where(["type" => 0], true)
            ->orderBy("system_package", "DESC")
            ->get();

        foreach ($this->packages as $packageData) {
            $package = new Runtime();

            $package->setId($packageData['id']);
            $package->setStoreID($packageData['store_id']);
            $package->name = $packageData['name'];
            $package->description = $packageData['description'];
            $package->icon = $packageData['icon'];
            $package->previewImage = $packageData['preview_image'];
            $package->license = $packageData['license'];
            $package->licenseUrl = $packageData['license_url'];
            $package->author = $packageData['author'];
            $package->setVersion($packageData['version']);
            $package->setMinimumAwtVersion($packageData['minimum_awt_version']);
            $package->setMaximumAwtVersion($packageData['maximum_awt_version']);
            $package->setInstallationDate($packageData['installation_date']);
            $package->installedByUsername = $packageData['installed_by'];

            switch ($packageData['type']) {
                case 1:
                    $package->setPackageType(EPackageType::Plugin);
                    break;
                case 2:
                    $package->setPackageType(EPackageType::Theme);
                    break;
            }

            if($packageData["system_package"] == 1) {
                $package->systemPackage = true;
            }

            if ($packageData['status'] === 1) {
                $package->packageStatus = EPackageStatus::Active;
            }

            $this->addPackage($package);

            if ($package->packageStatus === EPackageStatus::Active) {
                $this->active[$package->name] = $package;
            } else {
                $this->disabled[$package->name] = $package;
            }
        }
    }

    public function removePackage(int $id, bool $purge = false): void
    {
        if($purge && array_key_exists($id, $this->packages)) {
            $dataManager = new DataManager();
            $dataManager->purgeByOwnerId($id);
        }

        $this->databaseManager->__destruct();
        $this->databaseManager->table("awt_package")->where(["id" => $id])->delete();

        $this->removeFiles(PACKAGES . $this->packages[$id]->name);
    }

    private function removeFiles(string $dir): void
    {
        if(!file_exists($dir) || !is_dir($dir)) {
            return;
        }

        $scan = array_diff(scandir($dir), ['.', '..']);

        foreach ($scan as $item) {
            $childPath = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($childPath) && !is_link($childPath)) {
                $this->removeFiles($childPath);
            } else {
                unlink($childPath);
            }
        }

        rmdir($dir);
    }
}