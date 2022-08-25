<?php

namespace App\Telegram;

use App\Jobs\SendTelegramMessage;
use WeStacks\TeleBot\Handlers\CommandHandler;

class SendToAllCommand extends CommandHandler
{
    protected static $aliases = ['/send'];
    protected static $description = 'Send "/send" to send message';

    public function handle()
    {
        $chatId = $this->update->user()->id;
        if ($chatId != config('telebot.admin_id')) {
            SendTelegramMessage::dispatch($chatId, 'This command is for admin only')->onQueue('tgMessages');
        }
        AskMessageHandler::requestInput($this->bot, $chatId);

        return SendTelegramMessage::dispatch($chatId, 'Please, type your message.')->onQueue('tgMessages');
    }
}
