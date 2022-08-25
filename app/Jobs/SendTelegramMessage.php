<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;

class SendTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $chatID;
    protected string $text;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chatID, $text)
    {
        $this->chatID = $chatID;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        TeleBot::bot('bot')->sendMessage([
            'chat_id' => $this->chatID,
            'text' => $this->text
        ]);
    }
}
