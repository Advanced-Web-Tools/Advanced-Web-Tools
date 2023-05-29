<?php

namespace paging;

class paging
{
    protected array $adminPages;

    protected array $pluginPages;

    protected array $pages;

    public function __construct($pluginPages)
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
        );

        $this->pluginPages = $pluginPages;

        if (isset($_GET['page'])) echo "<title>" . WEB_NAME . " | " . $_GET['page'] . "</title>";
    }

    public function addBuiltInPage($name, $path, $description = '')
    {
        $this->pages[$name]['path'] = $path;
        $this->pages[$name]['description'] = $description;
    }

    public function getPage($selfCalled = false, $varName = '')
    {
        global $theme;
        global $menu;
        global $settings;
        global $aio;

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
}
