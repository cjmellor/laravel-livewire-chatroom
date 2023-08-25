<?php

namespace App\Http\Livewire;

use App\Enums\OnlineStatus;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Users extends Component
{
    /**
     * @var array<string, string>
     */
    protected $listeners = [
        'echo-presence:users,joining' => 'joining',
        'echo-presence:users,leaving' => 'leaving',
    ];

    public function joining($user)
    {
        cache()->add('online-status-'.$user['user_id'], OnlineStatus::Online);
    }

    public function leaving($user)
    {
        cache()->forget('online-status-'.$user['user_id']);
    }

    public function getUsersProperty(): array|Collection
    {
        return User::get(['id', 'name'])
            ->reject(fn ($user) => $user->is(auth()->user()));
    }

    public function render(): View
    {
        return view('livewire.users')->with('users', $this->users);
    }
}
