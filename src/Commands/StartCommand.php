<?php

namespace App\Commands;

use App\core\Bot;
use App\Interfaces\BotCommandInterface;

class StartCommand implements BotCommandInterface
{
    private Bot $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function handle($chatId, $text): void
    {
        // 🪵 Лог: підтвердження виклику
        file_put_contents(__DIR__ . '/../../debug.txt', "✅ StartCommand спрацював\n", FILE_APPEND);

        $msg = "👋 Привіт! Напиши назву міста або скористайся командою:\n/weather Львів";
        // відправляємо в телеграм айді і текст
        $this->bot->sendMessage($chatId, $msg);
    }
}

