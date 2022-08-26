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

class CopyTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TelegramAdmin;

    protected int $chatID;
    protected string $from_chat_id;
    protected string $message_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chatID, $from_chat_id, $message_id)
    {
        $this->chatID = $chatID;
        $this->from_chat_id = $from_chat_id;
        $this->message_id = $message_id;
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
            'from_chat_id' => $this->from_chat_id,
            'message_id' => $this->message_id,
        ];

        $parameters = $this->addMenu($parameters);

        $copied = TeleBot::bot('bot')->copyMessage($parameters)->toArray();

        TeleBot::bot('bot')->forwardMessage($copied);

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
