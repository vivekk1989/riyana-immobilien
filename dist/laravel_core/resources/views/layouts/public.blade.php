<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Riyana Immobilien') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
</head>

<body class="font-sans text-gray-900 antialiased flex flex-col min-h-screen">
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Riyana Immobilien" class="h-12 w-auto">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Startseite') }}
                        </x-nav-link>
                        <x-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.*')">
                            {{ __('Immobilien') }}
                        </x-nav-link>
                        <x-nav-link :href="route('contact.index')" :active="request()->routeIs('contact.*')">
                            {{ __('Kontakt') }}
                        </x-nav-link>
                    </div>
                </div>

                <!-- Settings Dropdown / Login -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{ __('Log in') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-auto rounded">
                <div>
                    <h3 class="font-bold text-lg">Riyana Immobilien</h3>
                    <p class="text-sm text-gray-400">Ihr Partner für Wohnen.</p>
                </div>
            </div>
            <div class="text-sm text-gray-400">
                <a href="#" class="hover:text-white mr-4">{{ __('Impressum') }}</a>
                <a href="#" class="hover:text-white">{{ __('Datenschutz') }}</a>
            </div>
        </div>
    </footer>
</body>

</html>