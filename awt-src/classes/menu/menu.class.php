<?php

namespace menu;
use database\databaseConfig;

class menu {
    public array $menus;
    public array $activeMenu;
    private object $dataBase;
    private object $mysqli;

    public function __construct()
    {
        $this->dataBase = new databaseConfig;
        $this->dataBase->checkAuthority() == 1 or die("Fatal error database access for " . $this->dataBase->getCaller() . " was denied");
        $this->mysqli = $this->dataBase->getConfig();
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_menus` ORDER BY `id` ASC;");
        $stmt->execute();
        $this->menus = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    public function getActiveMenus()
    {
        foreach($this->menus as $key => $value) {
            if($value['active'] == 1) {
                $this->activeMenu = $this->menus[$key];
            }
        }

        return $this->activeMenu;
    }

    public function retrieveMenuItems(){
        $this->getActiveMenus();
        return array_filter(explode("NEW_LINK", $this->activeMenu['items']));
    }

    public function createMenu(string $name, string $items)
    {   
        $active = 0;
        $stmt = $this->mysqli->prepare("INSERT INTO `awt_menus`(`name`, `items`, `active`) VALUES ('?','?','?')';");
        $stmt->bind_param("sss", $name, $items, $active);
        $stmt->execute();
    }

    public function updateMenu(string $name, string $items, string $active)
    {   
        $stmt = $this->mysqli->prepare("UPDATE  `awt_menus` SET `items` = ? , `active` = ? WHERE `name` = ?");
        $stmt->bind_param("sss", $items, $active, $name);
        $stmt->execute();
    }

    public function retrieveAllMenus() {
        return $this->menus;
    }

}