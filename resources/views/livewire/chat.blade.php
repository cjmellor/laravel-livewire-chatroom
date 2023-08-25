<div x-data="chat">
    <x-app-layout>
        <div>
            <form
                class="mt-4 mb-8 ml-12 space-x-2"
                wire:submit.prevent="sendMessage"
            >
                <label for="message">
                    <input
                        autofocus
                        class="dark:text-white dark:bg-gray-800"
                        id="message"
                        name="message"
                        type="text"
                        wire:model="message"
                        x-ref="message"
                    >
                </label>
                <button
                    class="py-2 px-3 text-white bg-blue-500 rounded-lg dark:text-white dark:bg-blue-900 hover:bg-blue-600"
                    type="submit"
                >Send Message
                </button>

                @error('message')
                <span class="dark:text-red-700">{{ $message }}</span>
                @enderror
            </form>
        </div>
        <div>
            <div>
                <h2 class="text-xl font-bold tracking-wider uppercase dark:text-white">Users online: <strong>{{ count($usersOnline) }}</strong></h2>
                <ul>
                    @foreach ($usersOnline as $user)
                        <li class="dark:text-white">
                            {{ $user['name'] }}
                            @if ($userTyping === $user['id'])
                                <x-typing-indicator class="inline-block ml-3" />
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div>
            <ul class="ml-12 space-y-4">
                @foreach ($messages as $message)
                    <li>
                        <span class="block font-bold dark:text-green-700">{{ $message->user->name }}</span>
                        <span
                            class="block dark:text-white"
                            x-bind:class='{ "text-5xl": hasOneEmoji("{{ $message->message }}") }'
                        >
                            {!! $message->message !!}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </x-app-layout>

    <script>
        window.addEventListener("alpine:init", () => {
            Alpine.data("chat", () => ({
                message: @entangle('message'),

                init () {
                    this.$watch("message", value => {
                        const whisperEventName = value === "" ? "stopped-typing" : "typing";

                        Echo.join("chat-room").whisper(whisperEventName, {
                            id: {{ auth()->id() }}
                        });
                    });

                    @this.
                    on("scrollToBottom", () => {
                        window.scrollTo({
                            top: document.body.scrollHeight,
                            behavior: "smooth"
                        });
                    });
                },

                hasOneEmoji (message, length = 1) {
                    return _.toArray(message).length === length && /\p{Emoji}/u.test(message);
                }
            }));
        });
    </script>
</div>
