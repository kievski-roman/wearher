<?php

namespace App\Services;
use Dotenv\Dotenv;

class WeatherService
{
    private $apiKey;
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->apiKey = $_ENV['API_WEATHER'] ?? null;
    }
    public function getWeather($text)
    {
        $text = urlencode(trim($text));
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$text}&appid={$this->apiKey}&units=metric&lang=ua";
        $json = file_get_contents($url, true);


        if(!$json){
            return null;
        }
        $data = json_decode($json, true);
        if(!$data){
            return null;
        }
        return [
            'city' => $data['name']??$text,
            'temperature' => $data['main']['temp']??0,
            'feels_like' => $data['main']['feels_like']??0,
            'humidity' => $data['main']['humidity']??0,
            'wind' => $data['wind']['speed']??0,
        ];
    }
}