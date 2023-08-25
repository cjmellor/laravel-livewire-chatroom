<div x-data="users">
    <x-app-layout>
        <ul class="m-12 bg-gray-500 rounded-lg border dark:border-gray-100">
            @foreach ($users as $user)
                <li class="flex gap-2 items-center py-4 px-6 text-2xl font-bold text-gray-800">
                    {{ $user->name }}
                    <span
                        @class([
                            'inline-block w-4 h-4 rounded-full',
                            'bg-red-800' => $user->is_offline,
                            'bg-green-800' => $user->is_online,
                        ])
                        x-ref="indicator"
                    >&nbsp;</span>
                </li>
            @endforeach
        </ul>
    </x-app-layout>

    <script>
        window.addEventListener("alpine:init", () => {
            Alpine.data("users", () => ({
                init() {
                    @this.on('cacheCleared', () => {
                        console.log('derp');
                    });
                }
            }))
        })
    </script>
</div>
