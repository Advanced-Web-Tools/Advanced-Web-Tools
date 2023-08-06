<?php 

namespace media;


use database\databaseConfig;

class albums {

    private object $database;
    private object $mysqli;
    protected array $albums = array();

    public function __construct()
    {
        $this->database = new databaseConfig();

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");
        $this->mysqli = $this->database->getConfig();
    }

    public function lateConstruct() {
        $this->database = new databaseConfig();

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");
        $this->mysqli = $this->database->getConfig();
    }

    public function getAllAlbums()
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_albums` WHERE 1;");
        $stmt->execute();
        $fetched = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($fetched as $key => $value) {
            $this->albums[$value['id']]["name"] = $value['name'];
        }

        return $this->albums;
    }

    public function deleteAlbum(string $id) {
        $stmt = $this->mysqli->prepare("DELETE FROM `awt_albums` WHERE `id` = ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();

        if(is_array($this->albums) && key_exists($id, $this->albums)) unset($this->albums[$id]);
    }


    public function createAlbum(string $name) {
        $stmt = $this->mysqli->prepare("INSERT INTO `awt_albums` (`name`) VALUES (?);");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

}