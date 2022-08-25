<?php

namespace App\Telegram;

use App\Jobs\SendTelegramMessage;
use App\Jobs\SendTelegramPhoto;
use App\Models\Customer;
use Picqer\Barcode\BarcodeGeneratorJPG;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Update;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start', '/s'];
    protected static $description = 'Send "/start" or "/s" to get started';

    public function handle()
    {
        $update = $this->update;
        $this->createCustomer($update);
        $this->sendBarcode($update);
    }

    protected function createCustomer(Update $update)
    {
        $first_name = $update->message->from->first_name;
        $last_name = $update->message->from->last_name;
        $chatid = $update->message->chat->id;
        $username = $update->message->from->username;

        $existingCustomer = Customer::where(['chatid' => $chatid]);
        if (!$existingCustomer->exists()) {
            $customer = new Customer();
            $customer->first_name = $first_name;
            $customer->last_name = $last_name;
            $customer->chatid = $chatid;
            $customer->username = $username;
            $customer->save();
        }
    }

    protected function sendBarcode(Update $update)
    {
        $generator = new BarcodeGeneratorJPG();
        $number = $this->getNumber();
        $path = config('barcode.store_path').$number.'.jpg';
        $resource = $generator->getBarcode($number, $generator::TYPE_EAN_13);
        file_put_contents($path, $resource);
        SendTelegramMessage::dispatch($update->message->chat->id, $number)->onQueue('tgMessages');
        SendTelegramPhoto::dispatch($update->message->chat->id, $path)->onQueue('tgMessages');
    }

    protected function getNumber(): int
    {
        return rand(10000000, 99999999);
    }
}
