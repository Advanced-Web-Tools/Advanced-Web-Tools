<?php

namespace packages\installer;

use admin\Admin;
use data\DataManager;
use data\enums\EDataOwnerType;
use data\enums\EDataType;
use data\models\DataModel;
use database\DatabaseManager;
use ErrorException;
use FilesystemIterator;
use object\ObjectHandler;
use packages\enums\EPackageType;
use packages\installer\interface\IPackageInstall;
use packages\installer\interface\IPackageUpdate;
use packages\manager\PackageManager;
use packages\ManifestReader;
use packages\Package;
use ZipArchive;

class PackageInstaller
{
    public Package $package;
    public Admin $admin;
    public ?int $storeId;
    private string $path;
    private DatabaseManager $databaseManager;
    private DataManager $dataManager;
    private array $packageFile;
    private string $tempName;
    private string $extension;
    private ?string $owner = null;
    private DataModel|bool $data;

    public function __construct(array $packageFile, ?int $storeId = null)
    {
        $this->packageFile = $packageFile;
        $this->package = new Package();
        $this->admin = new Admin();
        $this->storeId = $storeId;
        $this->databaseManager = new DatabaseManager();
        $this->dataManager = new DataManager();

        $this->tempName = substr(hash("md5", $this->packageFile['name']), 0, 10);
        $this->extension = explode(".", $this->packageFile['name'])[1];
    }

    public function setDataOwner(string $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @throws ErrorException
     */
    public function uploadPackage(): self
    {
        $name = $this->tempName . "." . $this->extension;
        $this->data = $this->dataManager
            ->uploadData($this->packageFile, $name, "temp", "System", $this->owner);

        return $this;
    }

    /**
     * @throws ErrorException
     */
    public function extractPackage(): self
    {
        $zip = new ZipArchive();

        $zip->open($this->data->getLocation(true));
        $zip->extractTo(TEMP . $this->tempName);
        $zip->close();

        $manifestReader = new ManifestReader(TEMP . $this->tempName);
        $this->package = $manifestReader->readManifest()->createPackage();
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function installPackage(): self
    {

        $installed = true;

        if(!is_dir(PACKAGES . str_replace(" ", "", $this->package->name)))
            $installed = false;

        if(!$installed) {
            mkdir(PACKAGES . str_replace(" ", "", $this->package->name), 0770, true);

            $this->dataManager->createOwnerDirectories($this->package->name);


            $type = match ($this->package->getPackageType()) {
                EPackageType::Plugin => 1,
                EPackageType::Theme => 2,
                EPackageType::System => 0,
            };

            $installedBy = null;
            if ($this->admin->checkAuthentication())
                $installedBy = $this->admin->getParam("id");

            $insert = [
                'store_id' => $this->storeId,
                'installed_by' => $installedBy,
                'name' => $this->package->name,
                'description' => $this->package->description,
                'icon' => null,
                'preview_image' => null,
                'license' => $this->package->license,
                'license_url' => $this->package->licenseUrl,
                'author' => $this->package->author,
                'version' => $this->package->getVersion(),
                'minimum_awt_version' => $this->package->getMinimumAwtVersion(),
                'maximum_awt_version' => $this->package->getMaximumAwtVersion(),
                'type' => $type,
                'system_package' => $this->package->systemPackage ? 1 : 0
            ];


            $this->package->setId($this->databaseManager->table("awt_package")->
            insert($insert)->executeInsert());

            if (file_exists(TEMP . $this->tempName . "/install.php")) {
                $installAction = ObjectHandler::createObjectFromFile(TEMP . $this->tempName . "/install.php");

                if ($installAction instanceof IPackageInstall) {
                    $installAction->postInstall($this->package->getId(), $this->package->name);
                }
            }

            if ($this->package->systemPackage) {
                $pm = new PackageManager();
                $pm->enablePackage($this->package->getId());
            }
        } else {

            $this->package->setId($this->databaseManager->table("awt_package")->select(["id"])->where(["name" => $this->package->name])->get()[0]["id"]);


            if($this->package->systemPackage) {
                $update["status"] = 1;
            }

            $update = [
                'store_id' => $this->storeId,
                'name' => $this->package->name,
                'description' => $this->package->description,
                'icon' => null,
                'preview_image' => null,
                'license' => $this->package->license,
                'license_url' => $this->package->licenseUrl,
                'author' => $this->package->author,
                'version' => $this->package->getVersion(),
                'minimum_awt_version' => $this->package->getMinimumAwtVersion(),
                'maximum_awt_version' => $this->package->getMaximumAwtVersion(),
                'system_package' => $this->package->systemPackage ? 1 : 0
            ];


            $this->databaseManager->table("awt_package")->where(["id" => $this->package->getId()])->update($update);

            if (file_exists(TEMP . $this->tempName . "/update.php")) {
                $installAction = ObjectHandler::createObjectFromFile(TEMP . $this->tempName . "/update.php");

                if ($installAction instanceof IPackageUpdate) {
                    $installAction->update($this->package->getId(), $this->package->name);
                }
            }
        }

        return $this;
    }

    public function transferPackageFiles(): self
    {
        $from = TEMP . $this->tempName;
        $to = PACKAGES . $this->package->name;

        $this->moveFiles($from, $to);

        return $this;
    }

    private function moveFiles(string $from, string $to): void
    {
        $files = new FilesystemIterator($from, FilesystemIterator::SKIP_DOTS);

        foreach ($files as $file) {
            $destPath = $to . DIRECTORY_SEPARATOR . $file->getBasename();

            if ($file->isDir()) {
                mkdir($destPath, 0755, true);
                $this->moveFiles($file->getPathname(), $destPath);
            } else {
                rename($file->getPathname(), $destPath);
            }
        }
    }


    /**
     * @throws ErrorException
     */
    public function extractData(): self
    {
        $dataDir = PACKAGES . $this->package->name . "/data";

        if (!file_exists($dataDir) || !is_dir($dataDir)) {
            return $this;
        }

        $allowedTypes = [
            "audio",
            "image",
            "video",
            "icon",
            "other",
            "document",
        ];

        $scanned = array_diff(scandir($dataDir), ['.', '..']);
        $filtered = array_intersect($scanned, $allowedTypes);

        foreach ($filtered as $dir) {
            $dirPath = $dataDir . "/" . $dir;
            if (!is_dir($dirPath)) {
                continue;
            }

            $content = array_diff(scandir($dirPath), ['.', '..']);
            $type = match ($dir) {
                "audio" => EDataType::Audio,
                "image" => EDataType::Image,
                "video" => EDataType::Video,
                "other" => EDataType::Other,
                "document" => EDataType::Document,
                "icon" => EDataType::Icon,
                default => null
            };

            foreach ($content as $file) {
                $filePath = $dirPath . "/" . $file;

                if (!is_file($filePath)) {
                    continue;
                }

                $fileData = [
                    "name" => basename($filePath),
                    "type" => mime_content_type($filePath),
                    "tmp_name" => $filePath,
                ];

                $data = $this->dataManager->uploadData($fileData, $fileData["name"], $dir, "Package", $this->package->name, true);

                if ($fileData["name"] == $this->package->getIcon()) {
                    $this->databaseManager->table("awt_package")->where(["id" => $this->package->getId()])
                        ->update([
                            "icon" => "/awt_data" . explode("awt_data", $data->getLocation())[1],
                        ]);
                }

                if ($file == $this->package->getPreviewImage()) {
                    $this->databaseManager->table("awt_package")->where(["id" => $this->package->getId()])
                        ->update([
                            "preview_image" => explode("public_html", $data->getLocation())[1],
                        ]);
                }

            }
        }


        return $this;
    }


    public function cleanUp(): void
    {
        $this->dataManager->fetchData($this->data->id);
        $this->dataManager->deleteData($this->data->id);
        $this->clearTempFiles();
    }

    private function clearTempFiles(?string $dir = null): void
    {
        $dir = $dir ?? TEMP . $this->tempName;
        if (!is_dir($dir)) {
            return;
        }

        $scan = array_diff(scandir($dir), ['.', '..']);

        foreach ($scan as $item) {
            $childPath = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($childPath) && !is_link($childPath)) {
                $this->clearTempFiles($childPath);
            } else {
                unlink($childPath);
            }
        }

        rmdir($dir);
    }

}