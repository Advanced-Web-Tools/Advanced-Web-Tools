<?php

use Dashboard\classes\dashboard\DashboardPage;
use redirect\Redirect;
use view\View;
use packages\manager\PackageManager;

final class AWTStoreController extends DashboardPage
{

    public string $storeURL = "https://development.advancedwebtools.com";

    private function pageHelper(): void {
        $this->adminCheck();

        if(!$this->admin->checkPermission(0))
            (new Redirect())->redirect("/dashboard/");

        $this->eventDispatcher->dispatch($this->event);
    }


    private function curlHelper(string $url): ?array {
        $curl = curl_init($this->storeURL . $url);

        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }

    /**
     * Returns filtered array of stores that have supported packages for this AWT
     * @param array $stores
     * @return array
     */
    private function filterByCompatible(array $stores): array {

        foreach($stores as $store_key => $store) {
            foreach($store["packages"] as $package_key => $package) {

                if(!$this->checkVersion($package)) {
                    unset($store["packages"][$package_key]);
                    continue;
                }

                $res = $this->checkIfInstalled($package);
                $stores[$store_key]["installed"] = $res["installed"];
                $stores[$store_key]["canUpdate"] = $res["canUpdate"];
            }

            if(count($store["packages"]) === 0)
                    unset($stores[$store_key]);
        }

        return $stores;
    }


    /**
     * Checks if package is supported by this awt version
     * @param array $package
     * @return bool
     */
    private function checkVersion(array $package): bool {

        $min = str_replace("v", "", $package["awtMin"]);
        $max = str_replace("v", "", $package["awtMax"]);
        $current = str_replace("v", "", AWT_VERSION);
        if(version_compare($current, $min, "<")) {
            return false;
        }

        if($package["awtMax"] != null && version_compare($current, $max, ">")) {
            return false;
        }

        return true;
    }


    /**
     * Return false if $old version is bigger or equal than the $new
     * True otherwise
     */
    private function compareVersions(string $old, string $new): bool {
        $current = str_replace("v", "", $old);
        $new = str_replace("v", "", $new);

        if(version_compare($current, $new, ">=")) {
            return false;
        }

        return true;
    }


    /**
     * Checks if package is installed and if it can be updated
     * @return array("installed" => false|true, "canUpdate" => false|true)
     */
    private function checkIfInstalled(array $fetched): array {
        $pm = new PackageManager();
        $pm->fetchPackages();
        $packages = $pm->getPackages();

        foreach($packages as $pkg) {
            $pkgName = is_array($pkg) ? $pkg["name"] : $pkg->name ?? null;
            $pkgVersion = is_array($pkg) ? $pkg["version"] : $pkg->version ?? null;

            if($pkgName === $fetched["name"]) {
                if($this->compareVersions($pkgVersion, $fetched["version"]) === true)
                    return ["installed" => true, "canUpdate" => true, "currentVersion" => $pkgVersion];

                return ["installed" => true, "canUpdate" => false, "currentVersion" => $pkgVersion];
            }
        }

        return ["installed" => false, "canUpdate" => false, "currentVersion" => null];
    }


    public function index(array|string $params): View
    {
        $this->pageHelper();
        $this->setTitle("Store");

        $bundle = $params;
        $bundle["stores"] = $this->curlHelper("/online_store/fetchStores/0/50");

        if($bundle["stores"] === null)
            return $this->view($this->getViewBundle($bundle));

        //Checks version compatibility and if its already installed
        $bundle["stores"] = $this->filterByCompatible($bundle["stores"]);

        $bundle["storeURL"] = $this->storeURL;

        return $this->view($this->getViewBundle($bundle));
    }


    public function store(array|string $params): View|Redirect
    {
        $this->pageHelper();

        $bundle = $params;

        if(empty($_GET["uid"]))
            return (new Redirect())->redirect("/dashboard/store/");

        $result = $this->curlHelper("/online_store/fetchStore/" . $_GET["uid"]);

        if($result === null)
            return $this->view($this->getViewBundle($bundle));

        $bundle["store"] = $result[array_key_first($result)];
        $this->setTitle($bundle["store"]["name"]);

        $compatible = false;

        foreach($bundle["store"]["packages"] as $package_key => $package) {
            $compatible = $this->checkVersion($package);

            if(!$compatible) {
                unset($bundle["store"]["packages"][$package_key]);
            } else {
                $compatible = true;
            }

            $res = $this->checkIfInstalled($package);
            $bundle["store"]["packages"][$package_key]["installed"] = $res["installed"];
            $bundle["store"]["packages"][$package_key]["canUpdate"] = $res["canUpdate"];

            if($res["installed"])
                $bundle["store"]["installed"] = true;

            if($res["installed"] && $package["version"] == $res["currentVersion"])
                $bundle["store"]["packages"][$package_key]["current"] = true;

        }

        $bundle["compatible"] = $compatible;
        $bundle["storeURL"] = $this->storeURL;

        if($compatible && $bundle["store"]["installed"])
            $bundle["store"]["canUpdate"] = $bundle["store"]["packages"][0]["canUpdate"];

        return $this->view($this->getViewBundle($bundle));
    }

}