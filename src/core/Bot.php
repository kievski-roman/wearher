<?php

namespace App\core;
use Dotenv\Dotenv;

class Bot
{
    private $apiTelegram;
    protected CommandFactory $commandFactory;
    public function __construct()
    {
        $this->commandFactory = new CommandFactory();

        // бібліотека для енвшкі
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $this->apiTelegram = $_ENV['API_TELEGRAM'] ?? null;
    }
    public function handle(): void
    {

        // просто отримуємо данні з телграм які нам треба
        $input =  file_get_contents('php://input');
        $data = json_decode($input, true);
        file_put_contents('log.json', json_encode($data, JSON_PRETTY_PRINT));
        $text = $data['message']['text'] ?? '';
        $chatId = $data['message']['chat']['id'] ?? '';


        //
        if (isset($data['callback_query'])) {
            $text = $data['callback_query']['data'];
            $chatId = $data['callback_query']['message']['chat']['id'];
        }
        if(!$text || !$chatId) return;


        //обирає команду з факторі яку написав користувач
        $command = $this->commandFactory->make($text, $this);
        $command->handle($chatId, $text);

    }



    // телеграм функція яка відправляє відповідь користовачу по /sendMessage і бере по айді і надсилає текст який треба
    public function sendMessage($chatId, $text){
        $url = "https://api.telegram.org/bot{$this->apiTelegram}/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
        ];
        file_get_contents($url.'?'.http_build_query($data));
    }

}