<x-admin-layout>
    <x-slot name="header">
        <x-slot name="header">
            {{ __('Edit Entry') }}: {{ $config->category->name }} ({{ $entry->date->format('d.m.Y') }})
        </x-slot>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST"
                    action="{{ route('admin.units.utilities.entries.update', ['unit' => $unit, 'config' => $config, 'entry' => $entry]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                        <input type="date" name="date" value="{{ old('date', $entry->date->format('Y-m-d')) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Value (Reading/Count)') }}</label>
                        <input type="number" step="0.0001" name="value" value="{{ old('value', $entry->value) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Calculated Cost (€) - ') }}<span
                                class="text-gray-500 font-normal">{{ __('Optional Override') }}</span></label>
                        <input type="number" step="0.01" name="cost" value="{{ old('cost', $entry->cost) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Proof Image') }}</label>
                        @if($entry->proof_image_path)
                            <div class="mb-2">
                                <a href="{{ Storage::url($entry->proof_image_path) }}" target="_blank"
                                    class="text-blue-600 hover:underline text-sm">{{ __('View Current Image') }}</a>
                            </div>
                        @endif
                        <input type="file" name="proof_image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('proof_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <button type="submit" class="btn-primary">{{ __('Update Entry') }}</button>
                        <a href="{{ route('admin.units.utilities.entries', ['unit' => $unit, 'config' => $config]) }}"
                            class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>

                        <button type="button" onclick="document.getElementById('delete-form').submit()"
                            class="ml-auto text-red-600 hover:text-red-800 text-sm font-medium">
                            {{ __('Delete Permanently') }}
                        </button>
                    </div>
                </form>

                <form id="delete-form"
                    action="{{ route('admin.units.utilities.entries.destroy', ['unit' => $unit, 'config' => $config, 'entry' => $entry]) }}"
                    method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
</x-admin-layout>