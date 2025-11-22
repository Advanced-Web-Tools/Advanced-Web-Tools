<?php

namespace cli\interfaces;

interface CLICommand
{
    public function getCommand(): string;
    public function getHelp(): string;
    public function getArguments(): array;
    public function execute(string $command, array $args = []): void;

    /**
     * Result should be in the format:
     *
     * @return string
     */
    public function result(): string;
}