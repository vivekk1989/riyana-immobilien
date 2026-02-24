<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Unit') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('admin.units.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="property_id"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('Property') }}:</label>
                    <select name="property_id" id="property_id"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="">{{ __('Select Property') }}</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                {{ $property->address }} ({{ $property->type }})
                            </option>
                        @endforeach
                    </select>
                    @error('property_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="unit_number"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('Unit Number') }}:</label>
                        <input type="text" name="unit_number" id="unit_number" value="{{ old('unit_number') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        @error('unit_number') <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="floor" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Floor') }}:</label>
                        <input type="number" name="floor" id="floor" value="{{ old('floor') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('floor') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="size"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('Size (sqm)') }}:</label>
                        <input type="number" step="0.01" name="size" id="size" value="{{ old('size') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        @error('size') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Price') }}
                            (€):</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('price') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="photos"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('Unit Photos') }}:</label>
                    <input type="file" name="photos[]" id="photos" multiple
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('photos.*') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Status') }}:</label>
                    <select name="status" id="status"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="for_rent" {{ old('status') == 'for_rent' ? 'selected' : '' }}>
                            {{ __('For Rent') }}
                        </option>
                        <option value="for_sale" {{ old('status') == 'for_sale' ? 'selected' : '' }}>
                            {{ __('For Sale') }}
                        </option>
                        <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>
                            {{ __('Rented') }}
                        </option>
                        <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>{{ __('Sold') }}
                        </option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                            {{ __('Archived') }}
                        </option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Create Unit') }}
                    </button>
                    <a href="{{ route('admin.units.index') }}"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</x-admin-layout>