<?php

namespace notifications;

use database\databaseConfig;
use plugins\plugins;

class attention
{

    private string $caller;
    private string $reason;
    private int $solved;
    private object $mysqli;
    private databaseConfig $databaseConfig;
    public function __construct(string $caller, string $message)
    {
        $this->caller = $caller;
        $this->reason = $message;

        $this->solved = 0;

        $this->databaseConfig = new databaseConfig();
    }



    private function raiseAttention(): void
    {
        $this->databaseConfig->checkAuthority() == 1 or die("Fatal error: Database access for " . $this->databaseConfig->getCaller() . " was denied");

        $this->mysqli = $this->databaseConfig->getConfig();

        $stmt = $this->mysqli->prepare("INSERT INTO `awt_attention` (`caller`, `reason`, `solved`) VALUES (?, ?, ?);");

        $stmt->bind_param("ssi", $this->caller, $this->reason, $this->solved);

        $stmt->execute();

        $stmt->close();

    }

    private function checkForUnresolvedAttention(): bool
    {
        $this->databaseConfig->checkAuthority() == 1 or die("Fatal error: Database access for " . $this->databaseConfig->getCaller() . " was denied");

        $this->mysqli = $this->databaseConfig->getConfig();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_attention` WHERE `caller` = ? AND `reason` = ? AND `solved` = '0';");

        $stmt->bind_param("ss", $this->caller, $this->reason);

        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $unresolvedIssuesExist = $result->num_rows;
        
        $stmt->close();
        
        return $unresolvedIssuesExist;
    }
    
    public function raiseOnMissingPlugin(string $pluginName): bool
    {
        global $loadedPlugins;
        
        foreach ($loadedPlugins as $key => $value) {
            if ($value["name"] == $pluginName)
            return false;
    }
    
    if (!$this->checkForUnresolvedAttention()) {
        $this->raiseAttention();
    }
    
    return true;
}


    public function raiseOnMissingDatabasePermission() : bool
    {
        $name = debug_backtrace()[0]['file'];
        $fileHash = hash_file("SHA512", $name);

        $authorized = $this->databaseConfig->checkIfFileAuthorized($fileHash, $name);


        if (!$authorized) {
            if (!$this->checkForUnresolvedAttention()) {
                $this->raiseAttention();
            }
            return true;
        }

        return false;
    }


    public function getUnresolved(): array
    {
        $this->databaseConfig->checkAuthority() == 1 or die("Fatal error: Database access for " . $this->databaseConfig->getCaller() . " was denied");
    
        $this->mysqli = $this->databaseConfig->getConfig();
    
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_attention` WHERE `solved` = '0';");
    
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        $unresolvedIssues = [];
    
        while ($row = $result->fetch_assoc()) {
            $unresolvedIssues[] = $row;
        }
    
        $stmt->close();
    
        return $unresolvedIssues;
    }


    public function setAsSolved(int $id) : void
    {
        $this->databaseConfig->checkAuthority() == 1 or die("Fatal error: Database access for " . $this->databaseConfig->getCaller() . " was denied");
    
        $this->mysqli = $this->databaseConfig->getConfig();
    
        $stmt = $this->mysqli->prepare("UPDATE `awt_attention` SET `solved` = 1 WHERE `id` = ?;");
    
        $stmt->bind_param("i", $id);
    
        $stmt->execute();
    
        $stmt->close();
    }



}