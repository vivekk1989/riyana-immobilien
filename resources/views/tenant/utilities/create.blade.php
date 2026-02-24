<x-tenant-layout>
    <x-slot name="header">{{ __('Record Entry') }}</x-slot>

    <div class="max-w-md mx-auto bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $config->category->name }}</h3>
            <p class="text-sm text-gray-500 mb-6">
                {{ __('Enter your new reading or cost.') }}
                @if($config->category->input_type == 'meter_reading')
                    {{ __('Previous') }}: <span
                        class="font-semibold">{{ $config->unit->entries()->where('utility_category_id', $config->utility_category_id)->latest('date')->first()?->value ?? 'N/A' }}</span>
                @endif
            </p>

            <form method="POST" action="{{ route('tenant.utilities.store', $config) }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <!-- Date -->
                <div>
                    <label for="date" class="form-label text-base">{{ __('Date') }}</label>
                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}"
                        class="form-input h-12 text-lg">
                    @error('date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Value -->
                <div>
                    <label for="value" class="form-label text-base">
                        {{ $config->category->input_type == 'meter_reading' ? __('New Meter Reading') : __('Cost (€)') }}
                    </label>
                    <input type="number" step="0.0001" name="value" id="value" value="{{ old('value') }}"
                        class="form-input h-12 text-lg font-bold text-gray-900" placeholder="0.00" required>
                    @error('value') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Photo Proof (Camera) -->
                <div>
                    <label for="proof_image"
                        class="form-label text-base mb-2 block">{{ __('Proof Image (Optional)') }}</label>
                    <label
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span
                                    class="font-semibold">{{ __('Tap to take photo') }}</span>
                            </p>
                            <p class="text-xs text-gray-500">JPG, PNG (MAX. 2MB)</p>
                        </div>
                        <input id="proof_image" name="proof_image" type="file" class="hidden" accept="image/*"
                            capture="environment">
                    </label>
                    @error('proof_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="btn-primary w-full h-12 text-lg font-bold flex items-center justify-center">
                        {{ __('Save Entry') }}
                    </button>
                    <a href="{{ route('tenant.utilities.index') }}"
                        class="block text-center mt-4 text-gray-500 text-sm hover:text-gray-700">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-tenant-layout>