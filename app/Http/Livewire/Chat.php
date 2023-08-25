<?php

namespace App\Http\Livewire;

use App\Concerns\CanTransform;
use App\Models\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    use CanTransform;

    public $message;

    public $usersOnline = [];

    public $userTyping;

    protected $listeners = [
        'echo-presence:chat-room,.MessageCreated' => 'render',
        'echo-presence:chat-room,here' => 'here',
        'echo-presence:chat-room,joining' => 'joining',
        'echo-presence:chat-room,leaving' => 'leaving',
        'echo-presence:chat-room,.client-typing' => 'typing',
        'echo-presence:chat-room,.client-stopped-typing' => 'stoppedTyping',
    ];

    protected array $rules = [
        'message' => ['required', 'string'],
    ];

    public function here($users)
    {
        $this->usersOnline = collect($users);
    }

    public function joining($user)
    {
        $this->usersOnline->push($user);
    }

    public function leaving($user)
    {
        $this->usersOnline = $this->usersOnline->reject(
            fn ($u) => $u['id'] === $user['id']
        );
    }

    public function typing($event)
    {
        $this->usersOnline->map(function ($user) use ($event): void {
            if ($user['id'] === $event['id']) {
                $user['typing'] = true;

                $this->userTyping = $user['id'];
            }
        });
    }

    public function stoppedTyping($event)
    {
        $this->usersOnline->map(function ($user) use ($event): void {
            if ($user['id'] === $event['id']) {
                unset($user['typing']);

                $this->userTyping = null;
            }
        });
    }

    public function sendMessage(): void
    {
        $this->validate();

        Auth::user()
            ->messages()
            ->create([
                'message' => $this->transform($this->message),
            ]);

        // $this->emitSelf('scrollToBottom');

        $this->message = '';
    }

public function render(): View
{
    return view('livewire.chat')
        ->with('messages', $this->messages);
}

    public function getMessagesProperty(): array|Collection
    {
        return Message::with('user')
            ->latest()
            ->get();
    }
}
