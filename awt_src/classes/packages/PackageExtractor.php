<?php

namespace packages;

use ZipArchive;

/**
 * Class PackageExtractor
 *
 * The PackageExtractor class is responsible for extracting packages from ZIP archives.
 * It can handle both uploaded files and specified archive paths, ensuring proper
 * extraction and management of the packages.
 */
class PackageExtractor
{
    private ?string $packageArchive;
    private string $destination;
    public string $path;
    private ZipArchive $zip;

    /**
     * PackageExtractor constructor.
     *
     * Initializes the extractor with an optional package archive path and a destination.
     *
     * @param string|null $packageArchive The path to the package archive.
     * @param string $destination The destination directory for extraction, defaults to TEMP.
     */
    public function __construct(?string $packageArchive = null, string $destination = TEMP)
    {
        $this->packageArchive = $packageArchive;
        $this->destination = $destination;
        $this->zip = new ZipArchive();
    }

    /**
     * Extracts the package from the ZIP archive.
     *
     * @return string|bool Returns the path to the extracted folder on success, or false on failure.
     */
    public function extractPackage(): string|bool
    {
        $archivePath = $this->packageArchive ?? ($_FILES['file']['tmp_name'] ?? null);

        if (!$archivePath || !file_exists($archivePath)) {
            return false;
        }

        $fileName = $this->packageArchive
            ? basename($this->packageArchive)
            : ($_FILES['file']['name'] ?? null);

        if (!$fileName) {
            return false;
        }

        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileType !== 'zip') {
            return false;
        }

        $hashName = substr(hash('SHA512', $fileName . time()), 0, 10);
        $targetFile = $this->destination . $hashName . '.' . $fileType;

        if (!$this->packageArchive && !move_uploaded_file($archivePath, $targetFile)) {
            return false;
        }

        $folderName = substr(hash('SHA512', $fileName), 0, 10);
        $folder = $this->destination . $folderName;
        $this->path = $folder;
        if (!is_dir($folder) && !mkdir($folder, 0755,true)) {
            return false;
        }

        $zipFilePath = $this->packageArchive ?? $targetFile;

        if ($this->zip->open($zipFilePath) === true) {
            $this->zip->extractTo($folder);
            $this->zip->close();
        } else {
            return false;
        }

        if (!$this->packageArchive) {
            unlink($targetFile);
        }

        return $folder;
    }
}
