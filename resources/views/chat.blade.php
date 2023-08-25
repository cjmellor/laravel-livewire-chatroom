<x-app-layout>
    <div
        class="grid grid-cols-3 grid-rows-1 max-w-full h-screen border-gray-300"
        x-data="chat"
    >
        <div
            class="col-span-2 overflow-y-auto border-r border-gray-300 space-y-4 bg-[#ECE5DD]"
            x-ref="messagePanel"
        >
            {{-- Messages --}}
            <template
                x-bind:key="message.id"
                x-for="message in messages"
            >
                <div
                    class="flex last:pb-4"
                    x-bind:class="message.user_id === {{ auth()->id() }} ? 'justify-end mx-3' : 'mx-3'"
                >
                    <div
                        class="relative p-2 text-sm leading-4 bg-white rounded-md shadow-lg max-w-fit after:absolute after:border-b-[10px] after:top-0"
                        x-bind:class="message.user_id === {{ auth()->id() }} ?
                            'bg-[#DCF8C6] after:border-l-[#DCF8C6] after:border-r-0 rounded-tl-md rounded-tr-none after:border-l-[10px] after:-right-2.5' :
                            'bg-gray-100 after:-left-2.5 after:border-r-gray-100 after:border-r-[10px] rounded-tl-none'"
                    >
                        <template x-if="message.user_id !== {{ auth()->id() }}">
                            <h4
                                class="inline-block text-xs font-bold leading-6"
                                x-text="message.user.name"
                            />
                        </template>
                        <div>
                            <span
                                x-bind:class="hasOneEmoji(message.message) ? 'block text-5xl text-center' : 'text-sm'"
                                x-html="message.message"
                            ></span>
                            <span class="inline-block float-right relative -bottom-1 pl-2">
                                <span
                                    class="inline-block text-[11px] text-black/50"
                                    x-text="moment(message.created_at).format('hh:mm a')"
                                >
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="bg-white">
            <div class="py-6 text-xl font-extrabold tracking-wider text-center text-white uppercase bg-emerald-600">
                Participants
            </div>

            {{-- List of users' online --}}
            <div class="space-y-4">
                <template
                    x-bind:key="user.id"
                    x-for="user in usersHere"
                >
                    <div class="flex items-center pt-2 pl-4 space-x-4 bg-white">
                        <img
                            alt="user"
                            class="w-10 h-10 rounded-xl ring-1 ring-gray-300"
                            src=""
                            x-bind:src="`https://ui-avatars.com/api/?name=${user.name}&background=random&format=svg`"
                        >
                        <div class="flex space-x-3">
                            <span
                                class="font-bold"
                                x-text="user.name"
                            ></span>
                            <template x-if="user.typing">
                                <x-typing-indicator />
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="col-span-full border-t border-gray-300">
            <form
                action="{{ route('messages.store') }}"
                method="post"
                x-on:keydown.meta.enter="sendMessage"
            >
                <label for="message">
                    <textarea
                        autocomplete="off"
                        autofocus
                        class="py-4 w-full border-none focus:ring-2 focus:ring-emerald-500"
                        name="message"
                        placeholder="Type your message here. Ctrl/⌘ + Enter to send."
                        required
                        rows="1"
                        x-model="message"
                        x-on:input="adjustHeight"
                        x-ref="message"
                    ></textarea>
                </label>
            </form>
        </div>
    </div>

</x-app-layout>

<script>
    document.addEventListener("alpine:init", async () => {
        const channel = Echo.join("chat-room");

        Alpine.data("chat", () => ({
            message: "",
            messages: [],
            usersHere: [],

            async init() {
                await channel.here(users => {
                        this.fetchMessages();
                        this.usersHere.push(...users);
                    })
                    .joining(user => this.usersHere.push(user))
                    .leaving(user => this.usersHere = _.reject(this.usersHere, {
                        id: user.id
                    }))
                    .listen(".MessageCreated", event => {
                        this.messages.push(event.message);
                        this.scrollToBottom();
                    })
                    .listenForWhisper("typing", event => {
                        this.usersHere.map(user => {
                            if (user.id === event.id) user.typing = true;

                            setTimeout(() => delete user.typing, 30000);
                        });
                    })
                    .listenForWhisper("stopped-typing", (event) => {
                        this.usersHere.map(user => {
                            if (user.id === event.id) delete user.typing;
                        });
                    });

                this.$watch("message", (value) => {
                    const whisper = value === "" ? "stopped-typing" : "typing";
                    channel.whisper(whisper, {
                        id: {{ auth()->id() }}
                    });

                    this.$refs.message.classList.remove("animate-shake-horizontal");
                });
            },

            async fetchMessages() {
                const response = await axios.get('{{ route('fetch-messages') }}');
                this.messages.push(...response.data);
                this.scrollToBottom();
            },

            async sendMessage() {
                await axios.post('{{ route('messages.store') }}', {
                    message: this.message
                }).then(() => {
                    this.message = "";
                    this.$refs.message.style.height = "auto";
                }).catch(() => {
                    this.$refs.message.classList.add("animate-shake-horizontal");

                    setTimeout(() =>
                        this.$refs.message.classList.remove("animate-shake-horizontal"), 500);
                });
            },

            scrollToBottom() {
                setTimeout(() => this.$refs.messagePanel
                    .scrollTo(0, this.$refs.messagePanel.scrollHeight), 100);
            },

            hasOneEmoji(message, length = 1) {
                return _.toArray(message).length === length && /\p{Emoji}/u.test(message);
            },

            adjustHeight() {
                this.$refs.message.style.height = "auto";
                this.$refs.message.style.height = this.$refs.message.scrollHeight + "px";
            }
        }));
    });
</script>
