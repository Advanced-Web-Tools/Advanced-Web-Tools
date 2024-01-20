<?php

namespace paging;

use admin\profiler;
use cache\cache;
use database\databaseConfig;

class paging extends cache
{
    protected array $adminPages;
    protected array $pluginPages;
    public array $pages;
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
            'Customize' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'customize.php',
            'Pages' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'pages.php',
            'Media' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'media.php',
            'pageEditor' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'pageEditor.php',
            'Menus' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'menus.php',
            'Mail' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'mail.php',
            'Theme Editor' => ADMIN . 'pages' . DIRECTORY_SEPARATOR . 'themeEditor.php',
        );

        $this->pluginPages = $pluginPages;

        if (isset($_GET['page']))
            echo "<title>" . WEB_NAME . " | " . $_GET['page'] . "</title>";

        $this->database = new databaseConfig;

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();
    }

    public function searchPage(string $column, string $value) : bool
    {
        $result = array();
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_paging` WHERE `" . $column . "` = ?;");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['id'], $result['name'], $result['description'], $result['content_1'], $result['content_2'], $result['status'], $result['token'], $result['override']);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {
            $stmt->close();
            return true;
        }

        return false;
    }


    public function addBuiltInPage(string $name, string $path, string $description = '')
    {
        global $builtInPages;
        $this->pages[$name]['path'] = $path;
        $this->pages[$name]['description'] = $description;
        $builtInPages[] = array('name'=> $name, 'builtIn' => true);
    }

    public function getPage(bool $loadAdmin = false, bool $selfCalled = false, string $varName = '')
    {
        global $theme;
        global $menu;
        global $settings;
        global $aio;
        global $render;

        $this->initializeCache();

        if ($this->cacheEnabled && $this->checkForCache($_GET['page'])) {
            return $this->readCache($_GET['page']);
        }

        $result = array();
        $status = 'live';

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_paging` WHERE `name` = ? AND `status` = ?;");
        $stmt->bind_param('ss', $_GET['page'], $status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['id'], $result['name'], $result['description'], $result['content_1'], $result['content_2'], $result['status'], $result['token'], $result['override']);
        $stmt->fetch();


        if ($stmt->num_rows == 1) {

            if ($this->cacheEnabled)
                $this->writePageCache($_GET['page'], $result['content_1'] . $result['content_2']);
        
            $stmt->close();
            return $result['content_1'] . $result['content_2'];

        } else {
            $stmt->close();
        }



        if ($selfCalled && $varName != '') {
            global $$varName;
            $$varName = $this;
        }

        if (isset($_GET['page']) && $loadAdmin) {
            if (array_key_exists($_GET['page'], $this->adminPages)) {
                if (file_exists($this->adminPages[$_GET['page']])) {
                    ob_start();
                    include_once $this->adminPages[$_GET['page']];
                    $page = ob_get_contents();
                    ob_end_clean();
                    return $page;
                }
            }

            if (array_key_exists($_GET['page'], $this->pluginPages)) {
                if (file_exists($this->pluginPages[$_GET['page']])) {
                    ob_start();
                    include_once $this->pluginPages[$_GET['page']];
                    $page = ob_get_contents();
                    ob_end_clean();
                    return $page;
                }
            }
            if (array_key_exists($_GET['page'], $this->pages)) {
                if (file_exists($this->pages[$_GET['page']]['path'])) {
                    ob_start();
                    include_once $this->pages[$_GET['page']]['path'];
                    $page = ob_get_contents();
                    ob_end_clean();
                    return $page;
                }
            }

            die("404\n Page not found.");
        }
    }

    public function getAllPages() : array
    {
        $result = array();

        $stmt = $this->mysqli->prepare("SELECT `id`, `name`, `description`, `status`, `override`, `token` FROM `awt_paging`");
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $result[] = $row;
        }

        return $result;
    }

    public function loadPreview(string $token, string $name)
    {
        $result = array();

        $status = "preview";

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_paging` WHERE `name` = ? AND `token` = ? AND `status` = ?;");
        $stmt->bind_param('sss', $name, $token, $status);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['id'], $result['description'], $result['name'], $result['content_1'], $result['content_2'], $result['status'], $result['token'], $result['override']);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {

            $page = $result['content_1'] . $result['content_2'];
            $stmt->close();
            return $page;
        } else {
            die("Page does not exist");
        }
    }

}
