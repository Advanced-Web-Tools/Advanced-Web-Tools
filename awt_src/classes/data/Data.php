<?php

namespace data;

use data\enums\EDataOwnerType;
use data\enums\EDataType;
use ErrorException;

class Data
{
    public ?int $id = null;
    public ?string $sDataType = null;
    public ?string $fileName = null;
    private EDataOwnerType $ownerType;
    private EDataType $type;
    public ?string $owner = null;
    private string $subDirectory;
    public string $sOwnerType;
    public ?string $location = null;

    /**
     * Data constructor.
     * Initializes the `Data` object with the owner type, and sets the corresponding
     * subdirectory path based on the owner type.
     *
     * @param ?EDataOwnerType $ownerType The type of the owner (User, Package, or System), default is null.
     */
    public function __construct(?EDataOwnerType $ownerType = null)
    {
        $this->ownerType = $ownerType;
        if ($this->ownerType !== null) {
            $this->setOwnerType($this->ownerType);
        }
        return $this;
    }

    /**
     * Converts the `Data` object into a string representation (JSON format).
     * The resulting string contains details like the file name, owner, file type,
     * subdirectory, and location.
     *
     * @return string JSON-encoded string of data object properties.
     */
    public function __toString(): string
    {
        $result = [
            "name" => $this->fileName,
            "owner" => $this->owner,
            "fileType" => $this->sDataType,
            "subDirectory" => $this->subDirectory,
            "location" => $this->location,
        ];

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * Sets subdirectory based of `EDataOwnerType`.
     * @param EDataOwnerType $ownerType
     * @return void
     */
    private function setOwnerType(EDataOwnerType $ownerType = null): void
    {
        if ($ownerType !== null) {
            $this->ownerType = $ownerType;
        }

        $this->subDirectory = match ($this->ownerType) {
            EDataOwnerType::System => "/awt_data/",
            EDataOwnerType::User => "/awt_data/media/uploads/",
            EDataOwnerType::Package => "/awt_data/media/packages/",
        };

        $this->sOwnerType = match ($this->ownerType) {
            EDataOwnerType::System => "System",
            EDataOwnerType::User => "User",
            EDataOwnerType::Package => "Package",
            default => "System"
        };
    }

    /**
     * Sets the data type for the `Data` object, such as audio, video, image, etc.
     *
     * @param EDataType $type The type of data.
     * @return self Returns the current instance of `Data` for method chaining.
     */
    public function setDataType(EDataType $type): self
    {
        $this->type = $type;
        $this->sDataType = match ($type) {
            EDataType::TempData => "temp",
            EDataType::Audio => "audio",
            EDataType::Video => "video",
            EDataType::Image => "image",
            EDataType::Document => "document",
            EDataType::Other => "other",
            EDataType::Icon => "icon",
            EDataType::Cache => "cache",
        };

        return $this;
    }

    /**
     * Return type of the data.
     * @return EDataType
     */
    public function getDataType(): EDataType
    {
        return $this->type;
    }

    /**
     * Sets the file name for the `Data` object.
     *
     * @param string $fileName The file name to be associated with this data.
     * @return self Returns the current instance of `Data` for method chaining.
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Sets the owner of the `Data` object.
     *
     * @param string $owner The name of the owner to be associated with this data.
     * @return self Returns the current instance of `Data` for method chaining.
     */
    public function setOwner(?string $owner): self
    {
        if ($owner !== null) {
            $this->owner = $owner;
        } else {
            $this->owner = null;
        }

        return $this;
    }

    /**
     * Sets the file location (path) for the `Data` object.
     *
     * @param string $location The file location path.
     * @return self Returns the current instance of `Data` for method chaining.
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Retrieves the location (path) of the file associated with the `Data` object.
     * If `localPath` is set to true, it appends the `DATA` constant to the location.
     * Throws an `ErrorException` if required fields like owner, file name, or data type
     * are not set.
     *
     * @param bool $localPath Whether to prepend the `DATA` constant to the file location.
     * @return string Returns the full file path.
     * @throws ErrorException If owner, file name, or data type is not set.
     */
    public function getLocation(bool $localPath = true): string
    {
        if ($this->ownerType == EDataOwnerType::Package && $this->owner === null)
            throw new ErrorException("No owner set");

        if ($this->fileName === null)
            throw new ErrorException("No file name set");

        if ($this->sDataType === null)
            throw new ErrorException("No dataType set");

        if ($this->owner === null || $this->ownerType == EDataOwnerType::System) {
            $this->location = $this->subDirectory . $this->sDataType . "/" . $this->fileName;
        } else {
            $this->location = $this->subDirectory . $this->sDataType . "/" . $this->owner . "/" . $this->fileName;
        }

        return $localPath ? ROOT . $this->location : $this->location;
    }

    /**
     * Deletes the file at the location associated with the `Data` object.
     *
     * @return bool Returns true if the file was successfully deleted, false otherwise.
     * @throws ErrorException If owner, file name, or data type is not set.
     */
    public function delete(): bool
    {
        $this->setLocation($this->getLocation(true));

        if (file_exists($this->location)) {
            return unlink($this->location);
        }

        return false;
    }

}