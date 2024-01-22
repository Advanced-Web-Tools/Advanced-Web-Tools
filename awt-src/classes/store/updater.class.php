<?php

namespace store;


use database\databaseConfig;
use ReflectionClass;
use ZipArchive;

class updater extends store
{
    public function __construct(string $apiCall = "", string $package = "", string $type = "")
    {
        parent::__construct($apiCall, $package, $type);
    }


    private function updatePlugin()
    {

    }

    private function updateTheme()
    {

    }

    
    private function replaceFileContent(string $file, string $old_content, string $new_content)
    {
        $lines = file($file);
        
        foreach ($lines as $line => $content) {
            if (str_contains($content, $old_content)) {
                $lines[$line] = $new_content . PHP_EOL;
            }
        }

        file_put_contents($file, implode("", $lines));
    }


    public function updateAWTVersion()
    {
        $this->data['api'] = "getLatestAWTVersion";
        
        $this->sendRequest();
        
        $this->response = json_decode($this->response, true);

        if (version_compare(AWT_VERSION, $this->response[0]["version"]) == -1) {

            file_put_contents(TEMP . DIRECTORY_SEPARATOR . "update.zip", fopen($this->response[0]["path"], 'r'));
            
            $zip = new ZipArchive();
            
            $zip->open(TEMP . DIRECTORY_SEPARATOR . 'update.zip');
            
            $zip->extractTo(ROOT);
            
            $zip->close();
            
            $this->updateDatabase();
            
            $this->updateConfigFile();
            
            unlink(TEMP . DIRECTORY_SEPARATOR . 'update.zip');
        }
    }
    
    

    private function getDatabaseConfig()
    {
        
        $db = new databaseConfig();
        
        if ($db->checkAuthority() == 1) {
            $reflectionClass = new ReflectionClass($db);
            $username = $reflectionClass->getProperty('username');
            $username->setAccessible(true);
            $info['username'] = $username->getValue();
            
            $password = $reflectionClass->getProperty('password');
            $password->setAccessible(true);
            $info['password'] = $password->getValue();
            
            $hostname = $reflectionClass->getProperty('hostname');
            $hostname->setAccessible(true);
            $info['hostname'] = $hostname->getValue();
            
            $database = $reflectionClass->getProperty('database');
            $database->setAccessible(true);
            $info['database'] = $database->getValue();
            
            $key = $reflectionClass->getProperty('key');
            $key->setAccessible(true);
            $info['key'] = $key->getValue();
            
            return $info;
        }
        
        return null;
    }
    

    private function updateDatabase()
    {
    
        
        $info = $this->getDatabaseConfig();
        
        $db_config_file = CLASSES . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'databaseConfig.class.php';
        
        $this->replaceFileContent($db_config_file, 'private static string $hostname = "";', 'private static string $hostname = "' . $info['hostname'] . '";');
        $this->replaceFileContent($db_config_file, 'private static string $database = "";', 'private static string $database = "' . $info['database'] . '";');
        $this->replaceFileContent($db_config_file, 'private static string $username = "";', 'private static string $username = "' . $info['username'] . '";');
        $this->replaceFileContent($db_config_file, 'private static string $password = "";', 'private static string $password = "' . $info['password'] . '";');
        $this->replaceFileContent($db_config_file, 'private static string $key = "";', 'private static string $key = "' . $info['key'] . '";');
        
        if (file_exists(ROOT . DIRECTORY_SEPARATOR . 'awt-database.sql')) {
            $db = new databaseConfig();
            $db->checkAuthority();
            $mysql = $db->getConfig();
            $sql = file_get_contents(ROOT . DIRECTORY_SEPARATOR . 'awt-database.sql');
            $mysql->multi_query($sql);
            unlink(ROOT . DIRECTORY_SEPARATOR . 'awt-database.sql');
        }
    
    }

    
    private function updateConfigFile()
    {
        $this->replaceFileContent(ROOT . DIRECTORY_SEPARATOR . 'awt-config.php', 'define(\'WEB_NAME\', "");', 'define("WEB_NAME", "' . WEB_NAME . '");');
        $this->replaceFileContent(ROOT . DIRECTORY_SEPARATOR . 'awt-config.php', 'define("WEB_NAME", "");', 'define("WEB_NAME", "' . WEB_NAME . '");');

        $this->replaceFileContent(ROOT . DIRECTORY_SEPARATOR . 'awt-config.php', 'define(\'AWT_VERSION\', "");', 'define("AWT_VERSION", "' . $this->response[0]['version'] . '");');
        $this->replaceFileContent(ROOT . DIRECTORY_SEPARATOR . 'awt-config.php', 'define("AWT_VERSION", "");', 'define("AWT_VERSION", "' . $this->response[0]['version'] . '");');

        $this->replaceFileContent(ROOT . DIRECTORY_SEPARATOR . 'awt-config.php', 'define("CONTACT_EMAIL", "");', 'define("CONTACT_EMAIL", "' . CONTACT_EMAIL . '");');
        
    }
    
}