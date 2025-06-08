<?php

namespace App\core;
use App\Commands\StartCommand;
use App\Commands\WeatherCommand;
use App\Interfaces\BotCommandInterface;

class CommandFactory
{
    private array $commands = [];
    public function __construct(){
        $files = glob(__DIR__ . '/../Commands/*Command.php');

        foreach ($files as $file) {
            $className = 'App\\Commands\\' . basename($file, '.php');

            $commandName = '/' . strtolower(str_replace('Command', '', basename($file, '.php')));

            $this->commands[$commandName] = $className;
        }

    }

        public function make(string $text, Bot $bot): BotCommandInterface
        {
            foreach ($this->commands as $prefix => $commandClass) {
                if(str_starts_with($text, $prefix)){
                    return new $commandClass($bot);
                }
            }
            return new StartCommand($bot);
        }

}