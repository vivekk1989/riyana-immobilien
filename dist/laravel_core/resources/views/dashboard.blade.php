@if(Auth::user()->role === 'tenant')
    <x-tenant-layout>
        <div class="space-y-6">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-medium uppercase">{{ __('Assigned Units') }}</p>
                        <p class="text-3xl font-bold text-gray-800">{{ Auth::user()->units->count() }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-medium uppercase">{{ __('Next Rent Due') }}</p>
                        <p class="text-xl font-bold text-gray-800">1st of Month</p> {{-- Placeholder --}}
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mt-8">{{ __('My Assigned Units') }}</h3>
            @if(Auth::user()->units->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach(Auth::user()->units as $unit)
                        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                            <div class="h-48 w-full bg-gray-200 relative">
                                @php
                                    $thumbPath = $unit->photos->first()?->path ?? $unit->property->photos->first()?->path;
                                @endphp
                                @if($thumbPath)
                                    <img src="{{ Storage::url($thumbPath) }}" alt="Property" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400">{{ __('No Image') }}
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-lg mb-1">{{ $unit->property->address }}</h4>
                                <p class="text-gray-600 text-sm mb-4">{{ __('Unit') }}: {{ $unit->unit_number }} •
                                    {{ $unit->floor ? __('Floor') . ' ' . $unit->floor : '' }}
                                </p>

                                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                    <div>
                                        <span class="block text-gray-500">{{ __('Size') }}</span>
                                        <span class="font-semibold">{{ $unit->size }} m²</span>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500">{{ __('Status') }}</span>
                                        <span class="capitalize">{{ str_replace('_', ' ', $unit->status) }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 border-t pt-4">
                                    <a href="{{ route('tenant.utilities.index', ['unit_id' => $unit->id]) }}"
                                        class="btn-primary w-full block text-center">
                                        {{ __('Manage Nebenkosten') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">{{ __('You do not have any units assigned yet.') }}</p>
            @endif
        </div>
    </x-tenant-layout>

@elseif(Auth::user()->role === 'admin')
    <x-admin-layout>
        <x-slot name="header">{{ __('Admin Dashboard') }}</x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.properties.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition">
                <div class="flex items-center mb-4 text-blue-600">
                    <svg class="h-8 w-8 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h5 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Properties') }}</h5>
                </div>
                <p class="font-normal text-gray-700">{{ __('Manage all properties and their details.') }}</p>
            </a>

            <a href="{{ route('admin.units.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition">
                <div class="flex items-center mb-4 text-green-600">
                    <svg class="h-8 w-8 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <h5 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Units') }}</h5>
                </div>
                <p class="font-normal text-gray-700">{{ __('Manage listing units and tenant assignments.') }}</p>
            </a>

            <a href="{{ route('admin.tenants.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition">
                <div class="flex items-center mb-4 text-orange-600">
                    <svg class="h-8 w-8 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h5 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Tenants') }}</h5>
                </div>
                <p class="font-normal text-gray-700">{{ __('Manage tenant accounts and credentials.') }}</p>
            </a>

            <a href="{{ route('admin.utilities.categories.index') }}"
                class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition">
                <div class="flex items-center mb-4 text-purple-600">
                    <svg class="h-8 w-8 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <h5 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Utilities') }}</h5>
                </div>
                <p class="font-normal text-gray-700">{{ __('Configure global utility categories.') }}</p>
            </a>
        </div>
    </x-admin-layout>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif