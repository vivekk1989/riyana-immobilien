<x-admin-layout>
    <x-slot name="header">
        <x-slot name="header">
            {{ __('Edit Configuration') }}: {{ $config->category->name }} ({{ $unit->property->address }} -
            {{ $unit->unit_number }})
        </x-slot>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST"
                    action="{{ route('admin.units.utilities.update', ['unit' => $unit, 'config' => $config]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Price / Unit') }} (€)</label>
                        <input type="number" step="0.0001" name="price_per_unit"
                            value="{{ old('price_per_unit', $config->price_per_unit) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('price_per_unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700">{{ __('Calculation Method (Optional)') }}</label>
                        <input type="text" name="calculation_method"
                            value="{{ old('calculation_method', $config->calculation_method) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ __('e.g. per m², per person') }}">
                        @error('calculation_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <button type="submit" class="btn-primary">{{ __('Update Configuration') }}</button>
                        <a href="{{ route('admin.units.utilities.index', $unit) }}"
                            class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
</x-admin-layout>