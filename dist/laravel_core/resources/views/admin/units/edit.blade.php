<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Unit') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.units.update', $unit) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="property_id"
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('Property') }}:</label>
                            <select name="property_id" id="property_id"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">{{ __('Select Property') }}</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id', $unit->property_id) == $property->id ? 'selected' : '' }}>
                                        {{ $property->address }} ({{ $property->type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('property_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="user_id"
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('Assigned Tenant') }}:</label>
                            <select name="user_id" id="user_id"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">-- None --</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('user_id', $unit->user_id) == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->name }} ({{ $tenant->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="unit_number"
                                    class="block text-gray-700 text-sm font-bold mb-2">{{ __('Unit Number') }}:</label>
                                <input type="text" name="unit_number" id="unit_number"
                                    value="{{ old('unit_number', $unit->unit_number) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                                @error('unit_number') <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="floor"
                                    class="block text-gray-700 text-sm font-bold mb-2">{{ __('Floor') }}:</label>
                                <input type="number" name="floor" id="floor" value="{{ old('floor', $unit->floor) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @error('floor') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="size"
                                    class="block text-gray-700 text-sm font-bold mb-2">{{ __('Size (sqm)') }}:</label>
                                <input type="number" step="0.01" name="size" id="size"
                                    value="{{ old('size', $unit->size) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                                @error('size') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Price') }}
                                    (€):</label>
                                <input type="number" step="0.01" name="price" id="price"
                                    value="{{ old('price', $unit->price) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @error('price') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status"
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('Status') }}:</label>
                            <select name="status" id="status"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="for_rent" {{ old('status', $unit->status) == 'for_rent' ? 'selected' : '' }}>{{ __('For Rent') }}</option>
                                <option value="for_sale" {{ old('status', $unit->status) == 'for_sale' ? 'selected' : '' }}>{{ __('For Sale') }}</option>
                                <option value="rented" {{ old('status', $unit->status) == 'rented' ? 'selected' : '' }}>
                                    {{ __('Rented') }}
                                </option>
                                <option value="sold" {{ old('status', $unit->status) == 'sold' ? 'selected' : '' }}>
                                    {{ __('Sold') }}
                                </option>
                                <option value="archived" {{ old('status', $unit->status) == 'archived' ? 'selected' : '' }}>{{ __('Archived') }}</option>
                            </select>
                            @error('status') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="photos"
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('Add Photos') }}:</label>
                            <input type="file" name="photos[]" id="photos" multiple
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('photos.*') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('Current Photos') }}:</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @forelse($unit->photos as $photo)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($photo->path) }}" alt="Unit Photo"
                                            class="w-full aspect-[4/3] object-cover rounded">

                                        <!-- Delete Button (Overlay) -->
                                        <button type="submit" form="delete-photo-{{ $photo->id }}"
                                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 opacity-75 hover:opacity-100"
                                            title="Delete Photo">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
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
                                {{ __('Update Unit') }}
                            </button>
                            <a href="{{ route('admin.units.index') }}"
                                class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>

                    <!-- Hidden Forms for Photo Deletion -->
                    @foreach($unit->photos as $photo)
                        <form id="delete-photo-{{ $photo->id }}" action="{{ route('admin.photos.destroy', $photo) }}"
                            method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                </div>
            </div>
    </div>
    </x-admin-layout>