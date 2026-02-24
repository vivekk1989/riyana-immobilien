<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Utility Category') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('admin.utilities.categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('Category Name') }}:</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="{{ __('e.g. Cold Water, Heating') }}" required>
                    @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="input_type"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('Input Type') }}:</label>
                    <select name="input_type" id="input_type"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="meter_reading" {{ old('input_type') == 'meter_reading' ? 'selected' : '' }}>
                            {{ __('Meter Reading (consumption based)') }}
                        </option>
                        <option value="fixed_cost" {{ old('input_type') == 'fixed_cost' ? 'selected' : '' }}>
                            {{ __('Fixed Cost / Manual Entry') }}
                        </option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        <strong>{{ __('Meter Reading') }}:</strong>
                        {{ __('Entries require a numeric value. Costs are calculated based on difference from previous reading * price.') }}<br>
                        <strong>{{ __('Fixed Cost') }}:</strong>
                        {{ __('Entries are direct costs or single unit counts.') }}
                    </p>
                    @error('input_type') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Create Category') }}
                    </button>
                    <a href="{{ route('admin.utilities.categories.index') }}"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</x-admin-layout>