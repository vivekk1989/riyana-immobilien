<x-public-layout>
    <div class="bg-gray-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('listings.index') }}"
                    class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Zurück zur Übersicht
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Gallery (2/3) -->
                <div class="lg:col-span-2">
                    @php
                        $allPhotos = $unit->photos->merge($unit->property->photos);
                        // Ensure we have at least one photo or a placeholder
                        $photosCount = $allPhotos->count();
                    @endphp

                    <div x-data="{ 
                        activeImage: '{{ $photosCount > 0 ? Storage::url($allPhotos->first()->path) : '' }}' 
                    }" class="space-y-4">

                        <!-- Main Image -->
                        <div
                            class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg overflow-hidden shadow-lg relative h-96">
                            @if($photosCount > 0)
                                <img :src="activeImage" alt="Main Property Photo" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 text-xl font-medium">
                                    Kein Bild verfügbar
                                </div>
                            @endif
                            <span class="absolute top-4 right-4 px-3 py-1 text-sm font-bold uppercase tracking-wider rounded text-white
                                {{ $unit->status == 'for_rent' ? 'bg-green-600' : 'bg-blue-600' }}">
                                {{ $unit->status == 'for_rent' ? 'Zu Vermieten' : 'Zu Verkaufen' }}
                            </span>
                        </div>

                        <!-- Thumbnails -->
                        @if($photosCount > 1)
                            <div class="flex space-x-4 overflow-x-auto pb-2">
                                @foreach($allPhotos as $photo)
                                    <button @click="activeImage = '{{ Storage::url($photo->path) }}'"
                                        class="flex-shrink-0 w-24 h-16 rounded-md overflow-hidden border-2 border-transparent hover:border-blue-500 focus:outline-none focus:border-blue-500 transition">
                                        <img src="{{ Storage::url($photo->path) }}" alt="Thumbnail"
                                            class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Description (Moved to Left Col below Gallery) -->
                    <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Beschreibung</h3>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $unit->description ?? 'Eine wunderschöne Einheit im Herzen der Stadt. Kontaktieren Sie uns für weitere Informationen oder einen Besichtigungstermin.' }}
                        </p>
                    </div>
                </div>

                <!-- Right Column: Details & Contact (1/3) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-6 space-y-6">
                        <!-- Key Details Card -->
                        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-blue-600">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $unit->property->address }}</h1>
                            <p class="text-gray-600 mb-6">{{ $unit->property->type }} • {{ __('Einheit') }}
                                {{ $unit->unit_number }}
                            </p>

                            <div class="flex justify-between items-end mb-6 pb-6 border-b border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500">Preis</p>
                                    <p class="text-3xl font-bold text-gray-900">
                                        {{ $unit->price ? '€' . number_format($unit->price, 0, ',', '.') : 'Auf Anfrage' }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-y-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Größe</p>
                                    <p class="font-semibold text-gray-900 text-lg">{{ $unit->size }} m²</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Zimmer</p>
                                    <p class="font-semibold text-gray-900 text-lg">{{ $unit->rooms ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Etage</p>
                                    <p class="font-semibold text-gray-900 text-lg">{{ $unit->floor ?? 'EG' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Verfügbar ab</p>
                                    <p class="font-semibold text-gray-900 text-lg">Sofort</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Action -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="font-bold text-lg text-gray-900 mb-4">{{ __('Interesse geweckt?') }}</h3>
                            <a href="{{ route('contact.index', ['unit_id' => $unit->id]) }}"
                                class="btn-primary w-full block text-center">
                                {{ __('Kontakt aufnehmen') }}
                            </a>
                            <p class="text-xs text-center text-gray-500 mt-3">
                                Unverbindlich anfragen via E-Mail.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>