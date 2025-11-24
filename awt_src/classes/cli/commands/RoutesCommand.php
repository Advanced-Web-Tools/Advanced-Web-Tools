<?php

namespace cli\commands;

use cli\interfaces\CLICommand;

class RoutesCommand implements CLICommand
{

    private string $lastResult = '';
    private array $routes;

    public function addRoutes(array $routes): void
    {
        $this->routes[] = $routes;
    }

    public function getCommand(): string
    {
        return 'routes';
    }

    public function getHelp(): string
    {
        return "Displays all registered routes";
    }

    public function getArguments(): array
    {
        return ["No arguments"];
    }

    public function execute(string $command, array $args = []): void
    {

        $colors = [
            'name' => "\033[1;34m",        // Blue
            'path' => "\033[1;32m",        // Green
            'action' => "\033[1;33m",      // Yellow
            'controller' => "\033[1;35m",  // Magenta
            'reset' => "\033[0m"
        ];


        $colWidths = [
            'name' => 20,
            'path' => 100,
            'action' => 20,
            'controller' => 25
        ];


        $this->lastResult = sprintf(
            "%-{$colWidths['name']}s %-{$colWidths['path']}s %-{$colWidths['action']}s %-{$colWidths['controller']}s\n",
            'Name', 'Path', 'Action', 'Controller'
        );
        $this->lastResult .= str_repeat('-', array_sum($colWidths)) . PHP_EOL;


        foreach ($this->routes as $router => $route) {
            foreach ($route as $finalRoute) {
                $this->lastResult .= sprintf(
                    "%s%-{$colWidths['name']}s%s %s%-{$colWidths['path']}s%s %s%-{$colWidths['action']}s%s %s%-{$colWidths['controller']}s%s\n",
                    $colors['name'], $finalRoute->name, $colors['reset'],
                    $colors['path'], $finalRoute->path, $colors['reset'],
                    $colors['action'], $finalRoute->action, $colors['reset'],
                    $colors['controller'], $finalRoute->controller->controllerName, $colors['reset']
                );
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function result(): string
    {
        return $this->lastResult;
    }
}