<?php

namespace content;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class fileScanner {
    protected function searchDirectory($directory)
    {
        $file_extensions = array('php', 'txt');
        $files = array();

        $dir_iterator = new RecursiveDirectoryIterator($directory);
        $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            if ($file->isFile() && in_array($file->getExtension(), $file_extensions)) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    protected function checkForForbidden($file)
    {
        $code = file($file);

        foreach ($code as $key => $value) {
            if (strpos($value, 'file(') !== false) {
                return 'Forbiden usage of file() function in ' . $file . ' on line: ' . $key + 1 . ' you should reconsider usage of this plugin!';
            }
            if (strpos($value, 'Reflection') !== false) {
                return 'Forbiden usage of Reflector class in ' . $file . ' on line: ' . $key + 1  . ' you should reconsider usage of this plugin!';
            }
        }

        return true;
    }

    protected function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->rrmdir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }


}