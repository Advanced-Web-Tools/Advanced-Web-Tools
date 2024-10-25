<?php

namespace PackageManager\classes\PackageManager;

use packages\enums\EPackageType;
use packages\manager\PackageManager;
use packages\Package;

/**
 * Class PackagePackageManager
 *
 * - Part of `PackageManager` package.
 * - Use with `Dashboard` package.
 *
 * Controls and fetches information of installed packages.
 */
final class PackagePackageManager extends PackageManager
{
    public function getSystem(): array
    {
        $this->fetchPackages();

        $return = [];


        foreach ($this->packages as $package) {
            if ($package instanceof Package) {
                if ($package->systemPackage) {
                    $return[] = $package->getInfo();
                }
            }
        }

        return $return;
    }

    public function getThemes(): array
    {
        $this->fetchPackages();
        $return = [];
        foreach ($this->packages as $package) {
            if ($package instanceof Package) {
                if ($package->getPackageType() === EPackageType::Theme) {
                    $return[] = $package->getInfo();
                }
            }
        }
        return $return;
    }

    public function getPlugins(): array
    {
        $this->fetchPackages();
        $return = [];
        foreach ($this->packages as $package) {
            if ($package instanceof Package) {
                if ($package->getPackageType() === EPackageType::Plugin && !$package->systemPackage) {
                    $return[] = $package->getInfo();
                }
            }
        }
        return $return;
    }

    public function getPackages(): array
    {
        $this->fetchPackages();
        $return = [];
        foreach ($this->packages as $package) {
            if ($package instanceof Package) {
                $return[] = $package->getInfo();
            }
        }
        return $return;
    }

}