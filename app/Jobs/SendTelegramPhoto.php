<?php

namespace App\Jobs;

use App\Traits\TelegramAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;
use WeStacks\TeleBot\Objects\KeyboardButton;

class SendTelegramPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TelegramAdmin;

    protected int $chatID;
    protected string $photo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chatID, $photo)
    {
        $this->chatID = $chatID;
        $this->photo = $photo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parameters = [
            'chat_id' => $this->chatID,
            'photo' => $this->photo
        ];

        $parameters = $this->addMenu($parameters);

        TeleBot::bot('bot')->sendPhoto($parameters);
    }

    function addMenu($parameters)
    {
        if ($this->checkAdmin($this->chatID)) {
            $keyboard = [
                [new KeyboardButton(['text' => '/stat']), new KeyboardButton(['text' => '/send'])],
            ];
            $parameters['reply_markup'] = [
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ];
        }
        return $parameters;
    }
}
