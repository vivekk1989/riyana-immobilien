<x-public-layout>
    <!-- Hero Section -->
    <div class="relative bg-gray-900 overflow-hidden">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover"
                src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1773&q=80"
                alt="Building">
            <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Riyana Immobilien</h1>
            <p class="mt-6 text-xl text-gray-300 max-w-3xl">
                {{ __('Finden Sie Ihr neues Zuhause. Modern, Zentral, Bezahlbar.') }}
            </p>
            <div class="mt-10">
                <a href="{{ route('listings.index') }}"
                    class="inline-block bg-blue-600 border border-transparent rounded-md py-3 px-8 font-medium text-white hover:bg-blue-700">
                    {{ __('Aktuelle Angebote ansehen') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Featured Properties -->
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-8">{{ __('Neueste Angebote') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($latestUnits as $unit)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 w-full bg-gray-200 relative">
                        @php
                            $thumbPath = $unit->photos->first()?->path ?? $unit->property->photos->first()?->path;
                        @endphp
                        @if($thumbPath)
                            <img src="{{ Storage::url($thumbPath) }}" alt="Property" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">{{ __('Kein Bild') }}</div>
                        @endif
                        <span
                            class="absolute top-2 right-2 px-2 py-1 bg-blue-600 text-white text-xs font-bold uppercase rounded">
                            {{ $unit->status == 'for_rent' ? __('Zu Vermieten') : __('Zu Verkaufen') }}
                        </span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $unit->property->address }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $unit->property->type }} - {{ __('Unit') }}
                            {{ $unit->unit_number }}
                        </p>
                        <div class="flex justify-between items-center mt-4">
                            <span
                                class="text-xl font-bold text-gray-900">{{ $unit->price ? '€' . number_format($unit->price, 0, ',', '.') : __('Preis auf Anfrage') }}</span>
                            <span class="text-sm text-gray-500">{{ $unit->size }} m²</span>
                        </div>
                        <a href="{{ route('listings.show', $unit) }}"
                            class="mt-4 block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded">
                            {{ __('Details ansehen') }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500 text-lg">{{ __('Keine aktuellen Angebote verfügbar.') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('listings.index') }}"
                class="text-blue-600 hover:text-blue-800 font-semibold">{{ __('Alle Angebote anzeigen') }} &rarr;</a>
        </div>
    </div>
</x-public-layout>