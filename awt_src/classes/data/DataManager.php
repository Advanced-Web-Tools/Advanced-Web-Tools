<?php

namespace data;

use data\enums\EDataOwnerType;
use data\enums\EDataType;
use database\DatabaseManager;
use ErrorException;

/**
 * Class DataManager
 *
 * This class is responsible for managing data entities within the system. It interacts
 * with the database to fetch, store, and manipulate data associated with different owners
 * and data types.
 */
class DataManager
{
    /**
     * @var array $data
     * An array that stores data objects.
     * Each element in the array represents a `Data` object, indexed by an identifier.
     */
    public array $data = [];
    private DatabaseManager $database;

    public function __construct()
    {
        $this->database = new DatabaseManager();
    }

    /**
     * Converts an array of data into `Data` objects and associates them with
     * their respective IDs.
     *
     * @param array $data Array of raw data fetched from the database.
     * @return array An array of `Data` objects, with keys being the data IDs.
     * @throws ErrorException On unknown data type.
     */
    public function arrayToData(array $data): array
    {

        $result = [];

        foreach ($data as $d) {

            $ownerType = match ($d["ownerType"]) {
                "User" => EDataOwnerType::User,
                "Package" => EDataOwnerType::Package,
                "System" => EDataOwnerType::System
            };

            $dataType = match ($d["dataType"]) {
                "image" => EDataType::Image,
                "video" => EDataType::Video,
                "audio" => EDataType::Audio,
                "document" => EDataType::Document,
                "other" => EDataType::Other,
                "temp" => EDataType::TempData,
                "icon" => EDataType::Icon,
                "cache" => EDataType::Cache,
                default => throw new ErrorException("Unknown data type: " . $d["dataType"]),
            };

            $data = new Data($ownerType);

            $data->setDataType($dataType)->setFileName($d["dataName"])->setOwner($d["ownerName"]);

            $result[$d['id']] = $data;
        }
        return $result;
    }

    /**
     * Fetches data from the database. If an ID is provided, it retrieves a specific
     * record; otherwise, it retrieves all data records. The data is then processed and
     * stored in the `$data` property of the object.
     *
     * @param int|null $id The ID of the data to fetch (optional).
     * @return self Returns the instance of DataManager.
     * @throws ErrorException
     */
    public function fetchData(?int $id = null): self
    {
        $this->database->__destruct();
        if ($id === null) {
            $data = $this->database->table("awt_data")->select()->where(["1" => "1"])->get();
        } else {
            $data = $this->database->table("awt_data")->select()->where(["id" => $id])->get();
        }

        $data = $this->arrayToData($data);

        foreach ($data as $key => $d) {
            $this->addDataToArray($d, $key);
        }

        return $this;
    }

    /**
     * Fetches data by a specific owner's ID and processes the fetched data
     * into `Data` objects, adding them to the `$data` property.
     *
     * @param int $id The ID of the owner whose data should be fetched.
     * @return self Returns the instance of DataManager.
     */
    public function fetchByOwnerId(int $id): self
    {
        $data = $this->database->table("awt_data")->select("*")->where(["ownerId" => $id])->get();

        $data = $this->arrayToData($data);

        foreach ($data as $key => $d) {
            $this->addDataToArray($d, $key);
        }

        return $this;
    }

    /**
     * Adds a `Data` object to the `$data` array. If an ID is provided, the data
     * is stored with that key; otherwise, it appends the data to the array.
     *
     * @param Data $data The data object to be added.
     * @param int|null $id The optional ID for indexing the data.
     * @return self Returns the instance of DataManager.
     */
    public function addDataToArray(Data $data, ?int $id = null): self
    {
        if ($id !== null) {
            $this->data[$id] = $data;
            return $this;
        }
        $this->data[] = $data;
        return $this;
    }

    /**
     * Retrieves data from the `$data` array. If an ID is provided, it returns the specific
     * data object; otherwise, it returns the entire array of data.
     *
     * @param int|null $id The ID of the data to retrieve (optional).
     * @return array|Data Returns the specific data object or an array of all data.
     */
    public function getData(?int $id = null): array|Data
    {
        if ($id === null) {
            return $this->data;
        }
        return $this->data[$id];
    }

    /**
     * Deletes the directories associated with an owner's name across different
     * subdirectories like "audio", "image", "video", etc.
     *
     * @param string $name The owner's name to delete the directories for.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */

    public function deleteOwnerDirectories(string $name): bool
    {
        $subDirs = [
            "audio",
            "image",
            "video",
            "icon",
            "other",
            "document",
        ];

        foreach ($subDirs as $subDir) {
            $path = DATA . "packages/media/" . $subDir . "/" . $name;
            if (file_exists($path)) {
                rmdir($path);
            } else {
                return false;
            }
        }

        return true;
    }

    public function createOwnerDirectories(string $name): void
    {
        $subDirs = [
            "audio",
            "image",
            "video",
            "icon",
            "other",
            "document",
        ];

        foreach ($subDirs as $subDir) {
            $path = DATA . "media/packages/" . $subDir . "/" . $name;
            if(!file_exists($path)) {
                mkdir($path, 0775);
            }
        }
    }

    /**
     * Uploads data (e.g., a file) and stores its metadata in the database.
     *
     * @param array $file The file to be uploaded, typically containing details such as the temporary file path.
     * @param string $fileName The name of the file to be saved.
     * @param EDataType $dataType The type of data (e.g., Image, Video, Audio).
     * @param EDataOwnerType $ownerType The type of owner for the data, defaulting to System.
     * @param string $ownerName The name of the owner, defaulting to "Advanced Web Tools".
     *
     * @return bool Returns true if the upload is successful, otherwise false.
     *
     * @throws ErrorException Thrown if any required information (e.g., owner name or file name) is missing.
     *
     * The method performs the following steps:
     * - Creates a new `Data` object based on the owner type.
     * - Sets the file name, data type, and owner on the `Data` object.
     * - Checks if the owner name is "AWT". If not, it looks up the package (owner) in the database.
     * - Inserts metadata about the file (e.g., owner type, owner name, file name) into the `awt_data` table.
     * - Moves the uploaded file to the proper location based on its type and owner.
     */
    public function uploadData(array $file, string $fileName, EDataType $dataType, EDataOwnerType $ownerType = EDataOwnerType::System, string $ownerName = "Advanced Web Tools", bool $local = false): Data|bool
    {

        $data = new Data($ownerType);
        $data->setFileName($fileName)->setDataType($dataType)->setOwner($ownerName);

        if($ownerName === "AWT") {
            $ownerId = 1;
        } else {
            $package = $this->database->table("awt_package")->select(["*"])->where(["name" => $ownerName])->get();

            if(count($package) === 0) {
                return false;
            }

            $ownerId = $package[0]["id"];
        }

        $ownerType = match ($ownerType) {
            EDataOwnerType::System => "System",
            EDataOwnerType::User => "User",
            EDataOwnerType::Package => "Package",
        };

        $id =$this->database->table("awt_data")->insert([
            "ownerType" => $ownerType,
            "ownerName" => $ownerName,
            "ownerId" => $ownerId,
            "dataName" => $fileName,
            "dataType" => $data->sDataType
        ])->executeInsert();

        if(!$local) {
            move_uploaded_file($file["tmp_name"], $data->getLocation(true));
        } else {
            rename($file["tmp_name"], $data->getLocation());
        }

        $data->id = $id;
        return $data;
    }

    /**
     * Purges all data associated with a given owner ID. It deletes the data from
     * both the database and the file system (directories) and clears the relevant
     * entries from the `$data` array.
     *
     * @param int $id The ID of the owner whose data should be purged.
     * @return bool Returns true if the purge was successful, false otherwise.
     */
    public function purgeByOwnerId(int $id): bool
    {
        $this->fetchByOwnerId($id);

        $ownerName = $this->data[array_key_first($this->data)]->owner;

        foreach ($this->data as $key => $d) {
            $status = $d->delete();

            unset($this->data[$key]);

            if(!$status)
                return false;
        }

        $result = $this->deleteOwnerDirectories($ownerName);

        if(!$result)
            return false;

        $this->database->table("awt_data")->where(["ownerId" => $id])->delete();

        return true;
    }

    public function deleteData(int $dataId) : bool {
        $this->fetchData($dataId);

        $res = $this->data[$dataId]->delete();

        if($res) {
            $this->database->__destruct();
            $this->database->table("awt_data")->where(["id" => $dataId])->delete();
        }

        return false;
    }

}