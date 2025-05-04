<?php

namespace packages;

use packages\enums\EPackageStatus;
use packages\enums\EPackageType;

/**
 * Class Package
 *
 * The Package class represents a software package that can be a plugin or theme.
 * It contains various properties that describe the package, such as its name,
 * version, author, and status. The class also provides methods to retrieve
 * and modify these properties.
 */
class Package
{
    protected ?int $id = null;                      // Unique identifier for the package.
    protected ?string $storeId = null;                 // Identifier for the store where the package is located.
    protected ?int $installedBy = null;             // ID of the user who installed the package.
    public string $name;                             // Name of the package.
    public ?string $description = null;              // Description of the package.
    public ?string $icon = null;                     // Path to the package icon.
    public ?string $previewImage = null;             // Path to the package preview image.
    protected ?string $version = null;               // Version of the package.
    protected string $minimumAwtVersion;             // Minimum required version of the AWT framework.
    protected ?string $maximumAwtVersion = null;     // Maximum supported version of the AWT framework.
    public EPackageType $packageType;             // Type of the package (Plugin or Theme).
    protected ?string $installationDate = null;      // Date the package was installed.
    public ?string $license = null;                  // License information for the package.
    public ?string $licenseUrl = null;               // URL to the license details.
    public ?string $author = null;                   // Author of the package.
    public ?string $packagePath = null;              // Path to the package files.
    public bool $systemPackage = false;              // Indicates if the package is a system package.
    public EPackageStatus $packageStatus = EPackageStatus::Disabled; // Current status of the package.

    public function __construct()
    {
        //TODO: Implement constructor for now obsolete
    }

    /**
     * Gets the ID of the package.
     *
     * @return int|null The unique identifier for the package or null if not set.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the ID of the package.
     *
     * @param int|null $id The unique identifier for the package.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the store ID of the package.
     *
     * @return int|null The store identifier or null if not set.
     */
    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    /**
     * Sets the store ID of the package.
     *
     * @param string|null $storeId The store identifier.
     */
    public function setStoreId(?string $storeId): void
    {
        $this->storeId = $storeId;
    }

    /**
     * Gets the ID of the user who installed the package.
     *
     * @return int|null The ID of the installer or null if not set.
     */
    public function getInstalledBy(): ?int
    {
        return $this->installedBy;
    }

    /**
     * Sets the ID of the user who installed the package.
     *
     * @param int|null $installedBy The installer user ID.
     */
    public function setInstalledBy(?int $installedBy): void
    {
        $this->installedBy = $installedBy;
    }

    /**
     * Gets the icon of the package.
     *
     * @return string|null The path to the icon or null if not set.
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Sets the icon of the package.
     *
     * @param string|null $icon The path to the icon.
     */
    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * Gets the preview image of the package.
     *
     * @return string|null The path to the preview image or null if not set.
     */
    public function getPreviewImage(): ?string
    {
        return $this->previewImage;
    }

    /**
     * Sets the preview image of the package.
     *
     * @param string|null $previewImage The path to the preview image.
     */
    public function setPreviewImage(?string $previewImage): void
    {
        $this->previewImage = $previewImage;
    }

    /**
     * Gets the version of the package.
     *
     * @return string|null The version string or null if not set.
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * Sets the version of the package.
     *
     * @param string|null $version The version string.
     */
    public function setVersion(?string $version): void
    {
        $this->version = $version;
    }

    /**
     * Gets the minimum AWT version required for the package.
     *
     * @return string|null The minimum AWT version or null if not set.
     */
    public function getMinimumAwtVersion(): ?string
    {
        return $this->minimumAwtVersion;
    }

    /**
     * Sets the minimum AWT version required for the package.
     *
     * @param string|null $minimumAwtVersion The minimum AWT version string.
     */
    public function setMinimumAwtVersion(?string $minimumAwtVersion): void
    {
        $this->minimumAwtVersion = $minimumAwtVersion;
    }

    /**
     * Gets the maximum AWT version supported by the package.
     *
     * @return string|null The maximum AWT version or null if not set.
     */
    public function getMaximumAwtVersion(): ?string
    {
        return $this->maximumAwtVersion;
    }

    /**
     * Sets the maximum AWT version supported by the package.
     *
     * @param string|null $maximumAwtVersion The maximum AWT version string.
     */
    public function setMaximumAwtVersion(?string $maximumAwtVersion): void
    {
        $this->maximumAwtVersion = $maximumAwtVersion;
    }

    /**
     * Sets the type of the package.
     *
     * @param EPackageType|null $packageType The type of the package (e.g., Plugin, Theme).
     */
    public function setPackageType(?EPackageType $packageType): void
    {
        $this->packageType = $packageType;
    }

    /**
     * Gets the type of the package.
     *
     * @return EPackageType|null The package type or null if not set.
     */
    public function getPackageType(): ?EPackageType
    {
        return $this->packageType;
    }

    /**
     * Gets the installation date of the package.
     *
     * @return string|null The installation date or null if not set.
     */
    public function getInstallationDate(): ?string
    {
        return $this->installationDate;
    }

    /**
     * Sets the installation date of the package.
     *
     * @param string|null $installationDate The installation date string.
     */
    public function setInstallationDate(?string $installationDate): void
    {
        $this->installationDate = $installationDate;
    }

    /**
     * Gets detailed information about the package.
     *
     * This method returns an array containing key details about the package,
     * including its ID, name, description, version, license, and status.
     *
     * @return array An associative array of package information.
     */
    public function getInfo(): array
    {
        $type = match ($this->packageType) {
            EPackageType::Plugin => "Plugin",
            EPackageType::Theme => "Theme",
            default => "Unknown",
        };

        $status = match ($this->packageStatus) {
            EPackageStatus::Disabled => "Disabled",
            EPackageStatus::Active => "Active",
            default => "Unknown",
        };

        if($this->systemPackage) {
            $type = "System";
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "icon" => $this->icon,
            "version" => $this->version,
            "minimumAwtVersion" => $this->minimumAwtVersion,
            "maximumAwtVersion" => $this->maximumAwtVersion,
            "installationDate" => $this->installationDate,
            "license" => $this->license,
            "licenseUrl" => $this->licenseUrl,
            "author" => $this->author,
            "installedBy" => $this->installedBy,
            "previewImage" => $this->previewImage,
            "system" => $this->systemPackage,
            "type" => $type,
            "status" => $status,
        ];
    }
}
