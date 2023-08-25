<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta
        content="width=device-width, initial-scale=1"
        name="viewport"
    >
    <meta
        content="{{ csrf_token() }}"
        name="csrf-token"
    >

    <title>{{ config('app.name', 'Chat') }}</title>

    <!-- Fonts -->
    <link
        href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Assets -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="overscroll-none font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow dark:bg-gray-800">
                <div class="py-6 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>

</html>
