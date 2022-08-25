<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;

class SendTelegramPhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        TeleBot::bot('bot')->sendPhoto([
            'chat_id' => $this->chatID,
            'photo' => $this->photo
        ]);
    }
}
