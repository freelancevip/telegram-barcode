<?php

namespace App\Telegram;

use App\Jobs\SendTelegramMessage;
use App\Traits\TelegramAdmin;
use WeStacks\TeleBot\Handlers\CommandHandler;

class SendToAllCommand extends CommandHandler
{
    use TelegramAdmin;

    protected static $aliases = ['/send'];
    protected static $description = 'Send "/send" to send message';

    public function handle()
    {
        $chatId = $this->update->user()->id;
        if (!$this->isAdmin()) {
            return SendTelegramMessage::dispatch($chatId, 'This command is for admin only')->onQueue('tgMessages');
        }
        AskMessageHandler::requestInput($this->bot, $chatId);

        return SendTelegramMessage::dispatch($chatId, 'Please, type your message.')->onQueue('tgMessages');
    }
}
