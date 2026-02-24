<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Property') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('admin.properties.update', $property) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Address') }}:</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    @error('address') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Type') }}:</label>
                    <input type="text" name="type" id="type" value="{{ old('type', $property->type) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    @error('type') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="photos"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('Add Photos') }}:</label>
                    <input type="file" name="photos[]" id="photos" multiple
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('photos.*') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Current Photos') }}:</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @forelse($property->photos as $photo)
                            <div class="relative group">
                                <img src="{{ Storage::url($photo->path) }}" alt="Property Photo"
                                    class="w-full aspect-[4/3] object-cover rounded">

                                <!-- Delete Button (Overlay) -->
                                <button type="submit" form="delete-photo-{{ $photo->id }}"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 opacity-75 hover:opacity-100"
                                    title="Delete Photo">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">{{ __('No photos uploaded.') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Update Property') }}
                    </button>
                    <a href="{{ route('admin.properties.index') }}"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>

            <!-- Hidden Forms for Photo Deletion -->
            @foreach($property->photos as $photo)
                <form id="delete-photo-{{ $photo->id }}" action="{{ route('admin.photos.destroy', $photo) }}" method="POST"
                    class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>
    </div>
</x-admin-layout>