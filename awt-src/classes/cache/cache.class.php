<?php

namespace cache;

use session\sessionHandler;

class cache extends SessionHandler
{
    public bool $cacheEnabled;
    private int $fileDuration;
    private int $sessionDuration;
    private string $location;
    private array $files;
    private object $settings;

    public function __construct()
    {
        $this->cacheEnabled = true;
        $this->sessionDuration = 300;
        $this->fileDuration = 600;
        $this->location = CACHE;
    }

    public function initializeCache()
    {
        $this->cacheEnabled = true;
        $this->sessionDuration = 300;
        $this->fileDuration = 10;
        $this->location = CACHE;
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
}
