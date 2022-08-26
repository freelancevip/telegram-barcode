<?php

namespace App\Telegram;

use App\Jobs\SendTelegramMessage;
use App\Jobs\SendTelegramPhoto;
use App\Models\Customer;
use Intervention\Image\Facades\Image;
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
        $last_name = $update->message->from->last_name ?? '';
        $chatid = $update->message->chat->id;
        $username = $update->message->from->username ?? '';

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
        $filename = $number.'.jpg';
        $path = config('barcode.store_path').$filename;
        $resource = $generator->getBarcode($number, $generator::TYPE_EAN_13);

        //
        $border = 10;
        $im = Image::make($resource);
        $width = $im->getWidth() + (2 * $border);
        $height = $im->getHeight() + (2 * $border);

        $im->resizeCanvas($width, $height)->save($path);

        $welcomeMsg = 'Добро пожаловать в наш чат-бот!';
        SendTelegramMessage::dispatch($update->message->chat->id, $welcomeMsg)->onQueue('tgMessages');
        SendTelegramMessage::dispatch($update->message->chat->id, $number)->onQueue('tgMessages');
        SendTelegramPhoto::dispatch($update->message->chat->id, $path)->onQueue('tgMessages');
    }

    protected function getNumber(): int
    {
        return rand(10000000, 99999999);
    }
}
