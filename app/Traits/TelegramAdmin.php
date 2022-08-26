<?php

namespace App\Traits;

trait TelegramAdmin
{
    function isAdmin(): bool
    {
        $admins = file(base_path().'/admins.txt', FILE_SKIP_EMPTY_LINES);
        return in_array($this->update->user()->id, $admins);
    }
}
