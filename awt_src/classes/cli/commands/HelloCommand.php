<?php


namespace cli\commands;


use cli\interfaces\CLICommand;


class HelloCommand implements CLICommand
{
    private string $lastResult = '';


    public function getCommand(): string
    {
        return 'hello';
    }


    public function getHelp(): string
    {
        return "hello [name]\nPrints 'Hello <name>' or 'Hello World' if name not given.";
    }


    public function getArguments(): array
    {
        return ['name' => 'optional name to greet'];
    }


    public function execute(string $command, array $args = []): void
    {
        $name = $args[0] ?? 'World';
        $this->lastResult = "Hello {$name}!";
    }


    public function result(): string
    {
        return $this->lastResult;
    }
}