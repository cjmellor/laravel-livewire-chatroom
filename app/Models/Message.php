<?php

namespace App\Models;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use BroadcastsEvents;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function broadcastOn($event): array
    {
        return [new PresenceChannel(name: 'chat-room')];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this,
            'user' => $this->user->only('name'),
        ];
    }
}
