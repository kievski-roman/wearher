<?php

namespace App\Interfaces;

interface BotCommandInterface
{
    public function handle(string $chatId, string $text): void;
}
