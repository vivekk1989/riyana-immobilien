<x-public-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Kontakt aufnehmen') }}</h2>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(isset($unit))
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <p class="font-bold text-blue-700">{{ __('Anfrage für Objekt:') }}</p>
                            <p class="text-blue-600">{{ $unit->property->address }} - {{ __('Einheit') }}
                                {{ $unit->unit_number }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="max-w-xl mx-auto space-y-6">
                        @csrf
                        @if(isset($unit))
                            <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                        @endif

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('E-Mail-Adresse')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('Telefonnummer (Optional)')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone"
                                :value="old('phone')" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Message -->
                        <div>
                            <x-input-label for="message" :value="__('Nachricht')" />
                            <textarea id="message" name="message"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-32"
                                required minlength="10">{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <!-- reCAPTCHA -->
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error('g-recaptcha-response')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                {{ __('Nachricht senden') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</x-public-layout>