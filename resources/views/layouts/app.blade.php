<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $isAdmin = request()->is('admin*') || request()->routeIs('admin.*');
        $isUser = request()->is('users*') || request()->routeIs('users.*');
        $isAuth = auth()->check();
        
        // Check if logged-in user is admin
        $userIsAdmin = auth()->check() && auth()->user()->role === 'admin' && !auth()->user()->is_client;

        // Use dark theme for admin, user area, and any authenticated pages (profile, etc.)
        $useDark = $isAdmin || $isUser || $isAuth;
    @endphp

    <body class="{{ $useDark ? 'dark font-sans antialiased' : 'font-sans antialiased' }}">
        <div class="min-h-screen {{ $useDark ? 'bg-slate-900 text-slate-100' : 'bg-gray-100' }}">
            @include('layouts.navigation')

            @if($isAdmin || ($userIsAdmin && !$isUser))
                @include('admin.partials.sidebar')
            @elseif($isAuth)
                @include('users.partials.sidebar')
            @endif

            {{-- Flash messages --}}
            @if (session('success') || session('error') || session('status'))
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="rounded-md bg-emerald-50 p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="rounded-md bg-blue-50 p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Page Heading -->
            <div class="{{ ($isAdmin || $isAuth) ? 'lg:pl-72' : '' }}">
                @if (isset($header))
                    <header class="{{ $useDark ? 'bg-transparent' : 'bg-white' }} shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </main>
            </div>
        </div>

        @stack('scripts')
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </body>
</html>
