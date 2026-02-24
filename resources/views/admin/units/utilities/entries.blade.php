<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Utility Entries') }}: {{ $config->category->name }} ({{ $unit->unit_number }})
        </h2>
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('admin.units.utilities.index', $unit) }}" class="text-blue-600 hover:underline">&larr;
            {{ __('Back to Utilities') }}</a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">{{ __('Recorded Entries') }}</h3>
                <a href="{{ route('admin.units.utilities.entries.create', ['unit' => $unit, 'config' => $config]) }}"
                    class="btn-primary text-sm px-3 py-2">
                    + {{ __('Add Entry') }}
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Value') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Cost') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Proof') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($entries as $entry)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entry->date->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                    {{ number_format($entry->value, 4) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entry->cost ? '€ ' . number_format($entry->cost, 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600">
                                    @if($entry->proof_image_path)
                                        <a href="{{ Storage::url($entry->proof_image_path) }}" target="_blank"
                                            class="hover:underline">{{ __('View Image') }}</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                    <a href="{{ route('admin.units.utilities.entries.edit', ['unit' => $unit, 'config' => $config, 'entry' => $entry]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium">{{ __('Edit') }}</a>
                                    <br>
                                    <span class="text-xs text-gray-400">
                                        {{ $entry->created_at->format('d.m H:i') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('No entries found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $entries->links() }}
            </div>
        </div>
    </div>
    </div>
</x-admin-layout>