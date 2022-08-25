<?php

namespace App\Telegram;

use App\Jobs\SendTelegramMessage;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use WeStacks\TeleBot\Handlers\RequestInputHandler;

class AskMessageHandler extends RequestInputHandler
{
    public function handle()
    {
        $data = $this->update->message()->toArray();
        $validator = Validator::make($data, [
            'text' => 'required|string|max:4096'
        ]);

        if ($validator->fails()) {
            return $this->sendMessage([
                'text' => 'Invalid input!'
            ]);
        }

        $this->acceptInput();
        $message = $validator->validated()['text'];

        $customers = Customer::all();

        foreach ($customers as $customer) {
            SendTelegramMessage::dispatch($customer->chatid, $message)->onQueue('tgMessages');
        }

        return SendTelegramMessage::dispatch(config('telebot.admin_id'),
            'Your message added to queue successfully.')->onQueue('tgMessages');
    }
}
