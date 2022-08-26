<?php

namespace App\Telegram;

use App\Jobs\CopyTelegramMessage;
use App\Jobs\SendTelegramMessage;
use App\Models\Customer;
use App\Vendor\CustomRequestInputHandler;

class AskMessageHandlerCustom extends CustomRequestInputHandler
{
    public function handle()
    {
        $this->acceptInput();

        $customers = Customer::all();

        foreach ($customers as $customer) {
            CopyTelegramMessage::dispatch(
                $customer->chatid,
                $this->update->message->chat->id,
                $this->update->message->message_id
            )->onQueue('tgMessages');
        }

        return SendTelegramMessage::dispatch($this->update->message->chat->id,
            'Your message added to queue successfully.')->onQueue('tgMessages');
    }
}
