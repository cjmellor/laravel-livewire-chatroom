<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\OnlineStatus;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use BroadcastsEvents;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'online_status' => OnlineStatus::class,
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function broadcastOn($event): array
    {
        return [new PresenceChannel('users')];
    }

    public function broadcastWith(): array
    {
        return [
            'user' => $this->only('id'),
        ];
    }

    public function isOnline(): Attribute
    {
        return Attribute::make(
            get: fn () => cache()->has('online-status-'.$this->id),
        );
    }

    public function isOffline(): Attribute
    {
        return Attribute::make(
            get: fn () => ! cache()->has('online-status-'.$this->id),
        );
    }
}
