<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
</head>

<body class="font-sans antialiased bg-gray-50">
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen relative">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/80 z-40 md:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 flex flex-col">
            <div class="p-4 border-b border-slate-700 flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold flex items-center space-x-2">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Riyana Immobilien" class="h-10 w-auto rounded">
                </a>
                <!-- Close Button (Mobile) -->
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2 rounded transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    {{ __('Dashboard') }}
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Management') }}
                    </p>
                </div>

                <a href="{{ route('admin.properties.index') }}"
                    class="block px-4 py-2 rounded transition {{ request()->routeIs('admin.properties.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    {{ __('Properties') }}
                </a>
                <a href="{{ route('admin.units.index') }}"
                    class="block px-4 py-2 rounded transition {{ request()->routeIs('admin.units.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    {{ __('Units') }}
                </a>
                <a href="{{ route('admin.tenants.index') }}"
                    class="block px-4 py-2 rounded transition {{ request()->routeIs('admin.tenants.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    {{ __('Tenants') }}
                </a>
                <a href="{{ route('admin.utilities.categories.index') }}"
                    class="block px-4 py-2 rounded transition {{ request()->routeIs('admin.utilities.categories.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    {{ __('Utilities') }}
                </a>
            </nav>

            <div class="p-4 border-t border-slate-700 text-xs text-slate-400">
                &copy; {{ date('Y') }} Riyana Immobilien
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full md:w-auto transition-all duration-300">
            <!-- Top Header -->
            <header class="bg-white shadow h-16 flex items-center justify-between px-6">
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Page Dropdown -->
                <div class="ml-auto flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }} (Admin)</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if(isset($header))
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $header }}</h2>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>