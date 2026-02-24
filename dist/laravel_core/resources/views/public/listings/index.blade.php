<x-public-layout>
    <div class="bg-gray-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Immobilien') }}</h1>

                <!-- Filter -->
                <form method="GET" action="{{ route('listings.index') }}" class="flex space-x-2">
                    <select name="status" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">{{ __('Alle Status') }}</option>
                        <option value="for_rent" {{ request('status') == 'for_rent' ? 'selected' : '' }}>
                            {{ __('Zu Vermieten') }}</option>
                        <option value="for_sale" {{ request('status') == 'for_sale' ? 'selected' : '' }}>
                            {{ __('Zu Verkaufen') }}</option>
                    </select>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($units as $unit)
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
                            <p class="text-gray-600 text-sm mb-2">{{ $unit->property->type }} - {{ __('Einheit') }}
                                {{ $unit->unit_number }}</p>
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

            <div class="mt-6">
                {{ $units->links() }}
            </div>
        </div>
    </div>
</x-public-layout>