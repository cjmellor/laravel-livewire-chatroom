<?php

namespace App\Broadcasting;

class ChatChannel
{
    public function __construct()
    {
    }

    /**
     * @return array<int|string|null, string>|false
     */
    public function join(): array|false
    {
        if (! auth()->check()) {
            return false;
        }

        return [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
        ];
    }
}
