<?php

namespace App\Vendor;

use WeStacks\TeleBot\Handlers\RequestInputHandler;

/**
 * Abstract class for creating Telegram update handlers.
 */
abstract class CustomRequestInputHandler extends RequestInputHandler
{
    public function trigger()
    {
        return static::class == (static::getState($this->bot)[$this->update->user()->id] ?? null);
    }
}
