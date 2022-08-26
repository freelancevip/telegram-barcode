<?php

namespace App\Telegram;

use App\Jobs\SendTelegramMessage;
use App\Models\Customer;
use App\Traits\TelegramAdmin;
use WeStacks\TeleBot\Handlers\CommandHandler;

class StatCommand extends CommandHandler
{
    use TelegramAdmin;

    protected static $aliases = ['/stat'];
    protected static $description = 'Send "/stat" to show statistics';

    public function handle()
    {
        $chatId = $this->update->user()->id;

        if (!$this->isAdmin()) {
            return SendTelegramMessage::dispatch($chatId, 'This command is for admin only')->onQueue('tgMessages');
        }

        $total = Customer::count();
        $weekCount = Customer::whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime('-7 days')))->count();

        $msg = "Total users: $total. Week: $weekCount";

        return SendTelegramMessage::dispatch($chatId, $msg)->onQueue('tgMessages');
    }
}
