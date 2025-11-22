<?php


namespace cli\commands;


use cli\interfaces\CLICommand;


class ClearCommand implements CLICommand
{
    private string $lastResult = '';


    public function getCommand(): string
    {
        return 'clear';
    }


    public function getHelp(): string
    {
        return "Clears the console screen\n";
    }


    public function getArguments(): array
    {
        return [];
    }


    public function execute(string $command, array $args = []): void
    {
        $this->lastResult = "\033[2J\033[;H";
    }


    public function result(): string
    {
        return $this->lastResult;
    }
}