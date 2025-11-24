<?php


namespace cli\commands;


use cli\interfaces\CLICommand;


class VersionCommand implements CLICommand
{
    private string $lastResult = '';


    public function getCommand(): string
    {
        return 'version';
    }


    public function getHelp(): string
    {
        return "Display current AWT version\nPrints application version.";
    }


    public function getArguments(): array
    {
        return [];
    }


    public function execute(string $command, array $args = []): void
    {
        $this->lastResult = AWT_VERSION;
    }


    public function result(): string
    {
        return $this->lastResult;
    }
}