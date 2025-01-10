<?php

namespace Theming\classes\Theme;

use model\Model;
use packages\manager\PackageManager;
use packages\Package;

final class ThemeModel extends Model
{
    private int $packageID;
    public Package $package;
    public function __construct(?int $id = null, array $data = [])
    {
        parent::__construct();

        if($id !== null) {
            $this->selectByID($id, "theming_theme");
        } else {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }

        $this->packageID = $this->getParam("package_id");

        $pm = new PackageManager();
        $pm->fetchPackages();

        $this->package = $this->filterPackages($pm->getPackages());

        $this->paramBlackList("package");
    }

    private function filterPackages(array $packages): ?Package
    {

        foreach ($packages as $package) {
            if(!$package instanceof Package)
                continue;
            $id = $package->getId();

            if($this->packageID === $id)
                return $package;

        }

        return null;
    }
}