<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BulkSMS Pro') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-gray-100">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
            <a href="/" class="mb-8 flex items-center space-x-3">
                <x-application-logo class="w-12 h-12 text-indigo-400" />
                <span class="text-2xl font-semibold text-white">{{ config('app.name', 'BulkSMS Pro') }}</span>
            </a>

            <div class="w-full max-w-md bg-white/5 backdrop-blur-sm border border-white/6 rounded-2xl p-6 shadow-lg">
                {{ $slot }}
            </div>

            <p class="mt-6 text-center text-sm text-gray-400">© {{ date('Y') == 2025 ? '2025' : '2025 - ' . date('Y') }} {{ config('app.name', 'BulkSMS Pro') }} — Built with Love & Good Taste</p>
        </div>
    </body>
</html>
