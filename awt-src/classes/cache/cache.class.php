<?php

namespace cache;

use session\sessionHandler;
use settings\settings;

class cache extends SessionHandler
{
    public bool $cacheEnabled;
    private int $fileDuration;
    private int $sessionDuration;
    private string $location;
    private array $files;
    private object $settings;
    public function initializeCache()
    {
        $this->settings = new settings;
        $this->cacheEnabled = $this->settings->getSettingsValue('enable_caching');
        $this->sessionDuration = $this->settings->getSettingsValue('page_caching_time');
        $this->fileDuration = $this->settings->getSettingsValue('cache_in_session_time');
        $this->location = CACHE;
        $this->sessionHandler();
    }

    public function scanCacheDirectory()
    {
        $this->files = scandir($this->location);
        unset($this->files[0]);
        unset($this->files[1]);
        return $this->files;
    }


    public function validateCache()
    {
        foreach ($this->files as $key => $value) {

            $time = filectime(CACHE . $value);

            if (time() - $time > $this->fileDuration) {
                unlink(CACHE . $value);
                unset($this->files[$key]);
            }
        }
    }

    public function checkForCache($name)
    {
        $this->scanCacheDirectory();
        $this->validateCache();

        if (in_array($name . '_cached.html', $this->files)) return true;
        return false;
    }

    public function readCache($name)
    {
        if ($this->checkForCache($name)) return file_get_contents(CACHE . $name . '_cached.html');
        return false;
    }

    public function writePageCache($name, $content)
    {
        $file = fopen(CACHE . $name . '_cached.html', 'w');
        fwrite($file, $content);
        fclose($file);
    }

    public function addToSessionCache($name, $value)
    {
        $this->sessionHandler();
        $_SESSION['cache'][$name] = $value;
        return 1;
    }

    public function checkForCacheSession($name)
    {
        $this->sessionHandler();

        if(time() - $_SESSION['cache']['started'] > 300) $this->sessionRemover('cache');

        if(!isset($_SESSION['cache'])) return false;

        if(!isset($_SESSION['cache'][$name])) return false;

        return true;

    }

    public function getCacheSession($name)
    {   
        if($this->checkForCacheSession($name)) return $_SESSION['cache'][$name];
        return false;
    }

    public function clearCache()
    {
        $this->sessionHandler();

        if(isset($_SESSION['cache'])) $this->sessionRemover('cache');

        foreach ($this->files as $key => $value) {
            unlink($this->location.$value);
        }

        return 1;

    }
}
