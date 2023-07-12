<?php

namespace paging;

use cache\cache;
use database\databaseConfig;

class paging extends cache
{
    protected array $adminPages;
    protected array $pluginPages;
    protected array $pages;
    private object $database;
    private object $mysqli;
    public function __construct(array $pluginPages)
    {
        $this->pages = array();

        $this->adminPages = array(
            'Dashboard' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'dashboard.php',
            'Themes' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'themes.php',
            'Plugins' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'plugins.php',
            'Store' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'store.php',
            'Settings' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'settings.php',
            'Accounts' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'accounts.php',
            'ThemeEditor' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'themeEditor.php',
            'Pages' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'pages.php',
            'pageEditor' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'pageEditor.php',
        );

        $this->pluginPages = $pluginPages;

        if (isset($_GET['page'])) echo "<title>" . WEB_NAME . " | " . $_GET['page'] . "</title>";

        $this->database = new databaseConfig;

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();
    }

    public function addBuiltInPage(string $name, string $path, string $description = '')
    {
        $this->pages[$name]['path'] = $path;
        $this->pages[$name]['description'] = $description;
    }

    public function getPage(bool $selfCalled = false, string $varName = '')
    {
        global $theme;
        global $menu;
        global $settings;
        global $aio;

        $this->initializeCache();

        if ($this->cacheEnabled && $this->checkForCache($_GET['page'])) {
            echo $this->readCache($_GET['page']);
            return 1;
        }

        $result = array();
        $status = 'live';

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_paging` WHERE `name` = ? AND `status` = ?;");
        $stmt->bind_param('ss', $_GET['page'], $status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['id'], $result['name'], $result['content_1'], $result['content_2'], $result['status'], $result['token'], $result['override']);
        $stmt->fetch();


        if ($stmt->num_rows == 1) {

            echo $result['content_1'] . $result['content_2'];
            if ($this->cacheEnabled) $this->writePageCache($_GET['page'],  $result['content_1'] . $result['content_2']);

            $stmt->close();
            return 1;
        }

        $stmt->close();

        if ($selfCalled && $varName != '') {
            global $$varName;
            $$varName = $this;
        }

        if (isset($_GET['page'])) {
            if (array_key_exists($_GET['page'], $this->adminPages)) {
                if (file_exists($this->adminPages[$_GET['page']])) {
                    include_once $this->adminPages[$_GET['page']];
                    return 1;
                }
            }

            if (array_key_exists($_GET['page'], $this->pluginPages)) {
                if (file_exists($this->pluginPages[$_GET['page']])) {
                    include_once $this->pluginPages[$_GET['page']];
                    return 1;
                }
            }
            if (array_key_exists($_GET['page'], $this->pages)) {
                if (file_exists($this->pages[$_GET['page']]['path'])) {
                    include_once $this->pages[$_GET['page']]['path'];
                    return 1;
                }
            }

            die("404\n Page not found.");
        }
    }


    public function uploadPage(string $name, string $page, string $status = "live", int $override = 0)
    {

        $result = array();
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_paging` WHERE `name` = ?;");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['id'], $result['name'], $result['content_1'], $result['content_2'], $result['status'], $result['token'], $result['override']);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {

            $this->updatePage($name, $page, $status, $override);

            $stmt->close();
            return 1;
        }

        $page_lenght = strlen($page);

        if ($page_lenght > 16777215) {
            $content_1 = substr($page, 0, 16777214);
            $content_2 = substr($page, 16777214, 16777214 * 2);
        } else {
            $content_1 = $page;
            $content_2 = "";
        }

        $token = hash("sha512", time());

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_paging`(`id`, `name`, `content_1`, `content_2`, `status`, `token`, `override`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssi", $name, $content_1, $content_2, $status, $token, $override);
        $stmt->execute();
        $stmt->close();
    }

    public function updatePage(string $name, string $page, string $status = "live", int $override = 0)
    {

        $page_length = strlen($page);

        if ($page_length > 16777215) {
            $content_1 = substr($page, 0, 16777214);
            $content_2 = substr($page, 16777214, 16777214 * 2);
        } else {
            $content_1 = $page;
            $content_2 = "";
        }

        $stmt = $this->mysqli->prepare("UPDATE `awt_paging` SET `content_1` = ?, `content_2` = ?, `status` = ?, `override` = ? WHERE `name` = ?");
        $stmt->bind_param("sssis", $content_1, $content_2, $status, $override, $name);
        $stmt->execute();
        $stmt->close();
    }

    public function getAllPages()
    {
        $result = array();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_paging`");
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $result[] = $row;
        }

        return $result;
    }

    public function createEmptyPage(string $name)
    {
        $content_1 = "";
        $content_2 = "";
        $status = "preview";
        $override = 0;

        $token = hash("sha512", time());

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_paging`(`id`, `name`, `content_1`, `content_2`, `status`, `token`, `override`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssi", $name, $content_1, $content_2, $status, $token, $override);
        $stmt->execute();
        $stmt->close();

        return "OK";
    }

    public function deletePage(int $id)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM `awt_paging` WHERE `id` = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        return "Page deleted with id: $id";
    }

    public function loadPage(int $page)
    {
        $result = array();
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_paging` WHERE `id` = ?;");
        $stmt->bind_param('i', $page);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['id'], $result['name'], $result['content_1'], $result['content_2'], $result['status'], $result['token'], $result['override']);
        $stmt->fetch();
        if ($stmt->num_rows == 1) {

            echo $result['content_1'] . $result['content_2'];
            $stmt->close();
            return 1;
        } else {
            die("Page does not exist");
        }
    }
}
