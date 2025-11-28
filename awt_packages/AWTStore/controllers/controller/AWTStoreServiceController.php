<?php

use controller\Controller;
use packages\installer\PackageInstaller;
use redirect\Redirect;
use view\View;
use admin\Admin;

final class AWTStoreServiceController extends Controller
{

    public string $storeURL = "https://development.advancedwebtools.com";

    private function adminCheck(): void {

        if(!isset($this->shared["admin"])) {
            $admin = new Admin();
        }
        else {
            $admin = $this->shared["admin"];
        }

        if(!$admin->checkAuthentication() || !$admin->checkPermission(0)) {
            (new Redirect())->redirect("/dashboard/");
        }
    }

    /**
     * @inheritDoc
     */
    public function index(array|string $params): Redirect
    {
        $this->adminCheck();

        if (!isset($_GET["remote_path"])) {
            return (new Redirect())->back();
        }

        $url = $this->storeURL . $_GET["remote_path"];

        $tmpFile = tempnam(sys_get_temp_dir(), "awt_zip_") . ".zip";

        $fp = fopen($tmpFile, 'w');
        if (!$fp) {
            die("Failed to create temporary file.");
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            fclose($fp);
            unlink($tmpFile);
            die("Failed to download package: $error");
        }

        curl_close($ch);
        fclose($fp);


        $fakeFile = [
            "name" => basename($url),
            "type" => "application/zip",
            "tmp_name" => $tmpFile,
            "error" => 0,
            "size" => filesize($tmpFile)
        ];

        $installer = new PackageInstaller($fakeFile);

        try {
            $installer
                ->setDataOwner("AWT")
                ->uploadPackage(true)
                ->extractPackage(true)
                ->installPackage()
                ->transferPackageFiles()
                ->extractData()
                ->cleanUp();
        } catch (Throwable $e) {
            unlink($tmpFile);

            if(DEBUG) {
                throw $e;
            }

            die("Failed to install package: <br>" . $e->getMessage() ." in ". $e->getFile() . ":" . $e->getLine() . "<br> Go back <a href='". (new Redirect())->getLast() ."'>here</a>.");
        }

        unlink($tmpFile);
        return (new Redirect())->back();
    }


    public function updateAWT(array|string $params): Redirect
    {
        $this->adminCheck();

        $url = $this->storeURL . $_GET["remote_path"];

        $tmpFile = tempnam(sys_get_temp_dir(), "awt_zip_") . ".zip";

        $fp = fopen($tmpFile, 'w');
        if (!$fp) {
            die("Failed to create temporary file.");
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            fclose($fp);
            unlink($tmpFile);
            die("Failed to download package: $error");
        }

        curl_close($ch);
        fclose($fp);


        $db_config = ROOT . "awt_db.php";

        $db_config_backup = ROOT . "awt_db_backup.php";

        copy($db_config, $db_config_backup);
        unlink($db_config);

        $zip = new ZipArchive();
        $res = $zip->open($tmpFile);
        if ($res === TRUE) {
            $zip->extractTo(ROOT);
        }

        copy($db_config_backup, $db_config);
        unlink($db_config_backup);

        if(file_exists(ROOT . "awt_update.php")) {
            require_once ROOT . "awt_update.php";
            unlink(ROOT . "awt_update.php");
        }
        unlink($tmpFile);

        return (new Redirect())->back();
    }


}