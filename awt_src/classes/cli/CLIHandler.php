<?php

namespace cli;


use cli\interfaces\CLICommand;


class CLIHandler
{
    /**
     * Stores commands in format: "command" => CLICommand
     *
     * @var CLICommand[]
     */
    private array $commands = [];


    public function addCommand(CLICommand $command): void
    {
        $this->commands[$command->getCommand()] = $command;
    }


    /**
     * Returns list of registered command names
     *
     * @return string[]
     */
    public function getCommands(): array
    {
        return array_keys($this->commands);
    }


    /**
     * Execute a registered command
     *
     * @param string $command
     * @param array $args
     * @return void
     */
    public function execute(string $command, array $args = []): void
    {
        if (!isset($this->commands[$command])) {
            echo "Unknown command: {$command}\n";
            echo $this->getGlobalHelp();
            return;
        }


        $cmd = $this->commands[$command];
        $cmd->execute($command, $args);
        $output = $cmd->result();

        if ($output !== '') {
            echo $output . PHP_EOL;
        }
    }


    /**
     * Print help for a specific command or all commands
     *
     * @param string|null $command
     * @return void
     */
    public function help(?string $command = null): void
    {
        if ($command === null) {
            echo $this->getGlobalHelp();
            return;
        }


        if (!isset($this->commands[$command])) {
            echo "No such command: {$command}\n";
            return;
        }


        $args = "";

        foreach ($this->commands[$command]->getArguments() as $arg => $value) {
            $args .= "\n{$arg}: {$value}";
        }


        echo $this->commands[$command]->getHelp() . PHP_EOL . "Arguments: {$args} ". PHP_EOL;
    }


    private function getGlobalHelp(): string
    {
        $lines = ['Available commands:'];
        foreach ($this->commands as $name => $cmd) {
            $lines[] = sprintf(" %-15s %s", $name, strtok($cmd->getHelp(), "\n"));
        }


        $lines[] = "\nRun: php awt <command> [args...]";
        $lines[] = "Use 'help <command>' for full command help.";


        return implode(PHP_EOL, $lines) . PHP_EOL;
    }
}
