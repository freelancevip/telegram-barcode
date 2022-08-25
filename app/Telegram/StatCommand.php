<?php

namespace App\Telegram;

use App\Jobs\SendTelegramMessage;
use App\Models\Customer;
use WeStacks\TeleBot\Handlers\CommandHandler;

class StatCommand extends CommandHandler
{
    protected static $aliases = ['/stat'];
    protected static $description = 'Send "/stat" to show statistics';

    public function handle()
    {
        $chatId = $this->update->user()->id;

        $total = Customer::count();
        $weekCount = Customer::whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime('-7 days')))->count();

        $msg = "Total users: $total. Week: $weekCount";

        SendTelegramMessage::dispatch($chatId, $msg)->onQueue('tgMessages');
    }
}
