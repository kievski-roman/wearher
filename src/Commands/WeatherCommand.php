<?php

namespace App\Commands;

use App\core\Bot;
use App\Interfaces\BotCommandInterface;
use App\Services\WeatherService;

class WeatherCommand implements BotCommandInterface
{
        private $bot;
        public function __construct(Bot $bot){
            $this->bot = $bot;
        }
        public function handle($chatId, $text): void
        {
            $title = str_starts_with($text, "/weather")
                ?trim(str_ireplace("/weather", "", $text))
                :trim($text);
            if(!$title){
                $this->bot->sendMessage($chatId,"введіть наприклад: /weather Амстердам ") ;
                return;
            }
            $data = (new WeatherService())->getWeather($title);
            if(!$data){
                $this->bot->sendMessage($chatId,"Не має такого міста". "*{$title}*") ;
                return;
            }
            $msg = "Місто :{$data['city']}\n";
            $msg .= "Температура :{$data['temperature']}\n";
            $msg .= "Відчувається як :{$data['feels_like']}\n";
            $msg .= "Вологість :{$data['humidity']}\n";
            $msg .= "Вітер :{$data['wind']}\n";


            // відправляємо в телеграм айді і текст
            $this->bot->sendMessage($chatId, $msg);
        }

}