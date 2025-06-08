<?php

namespace App\core;
use App\Commands\StartCommand;
use App\Commands\WeatherCommand;
use App\Interfaces\BotCommandInterface;
use App\Services\WeatherService;
class CommandFactory
{
    private array $commands = [];
    private WeatherService $weatherService;


    // Конструктор фабрики — тут створюємо всі команди та WeatherService
    public function __construct(){
        $files = glob(__DIR__ . '/../Commands/*Command.php');

        foreach ($files as $file) {
            $className = 'App\\Commands\\' . basename($file, '.php');

            $commandName = '/' . strtolower(str_replace('Command', '', basename($file, '.php')));

            $this->commands[$commandName] = $className;
        }


        // Створюємо WeatherService один раз
        $this->weatherService = new WeatherService();

    }


            // Створюємо відповідну команду в залежності від тексту
        public function make(string $text, Bot $bot): BotCommandInterface
        {
            foreach ($this->commands as $prefix => $commandClass) {
                if(str_starts_with($text, $prefix)){

                    // Якщо команда — це WeatherCommand, передаємо WeatherService через DI
                    if ($commandClass === \App\Commands\WeatherCommand::class) {
                        return new $commandClass($bot, $this->weatherService); // ← ось DI
                    }
            // Всі інші команди отримують тільки Bot
                    return new $commandClass($bot);
                }
            }

            // Якщо нічого не знайшли — повертаємо StartCommand
            return new StartCommand($bot);
        }

}