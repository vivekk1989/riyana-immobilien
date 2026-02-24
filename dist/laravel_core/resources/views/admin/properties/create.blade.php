<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Property') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Address') }}:</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    @error('address') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Type') }}:</label>
                    <input type="text" name="type" id="type" value="{{ old('type') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="{{ __('e.g. Apartment Complex, Single House') }}" required>
                    @error('type') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="photos" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Photos') }}:</label>
                    <input type="file" name="photos[]" id="photos" multiple
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('photos.*') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Create Property') }}
                    </button>
                    <a href="{{ route('admin.properties.index') }}"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</x-admin-layout>