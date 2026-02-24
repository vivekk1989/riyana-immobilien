<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Units') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="mb-4 flex justify-between items-center">
                <p class="text-gray-600">{{ __('Overview of all units.') }}</p>
                <a href="{{ route('admin.units.create') }}" class="btn-primary">
                    + {{ __('New Unit') }}
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Photo') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Property') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Unit Details') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Price') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($units as $unit)
                                            <tr class="hover:bg-gray-50 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="h-12 w-16 bg-gray-200 rounded overflow-hidden">
                                                        @php
                                                            $thumb = $unit->photos->first() ?? $unit->property->photos->first();
                                                        @endphp
                                                        @if($thumb)
                                                            <img src="{{ Storage::url($thumb->path) }}" alt="Unit"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <div class="flex items-center justify-center h-full text-xs text-gray-400">{{ __('No Img') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="font-medium">{{ $unit->property->address }}</div>
                                                    <div class="text-gray-500 text-xs">{{ $unit->property->type }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ __('Unit') }} {{ $unit->unit_number }} <span class="mx-1">•</span> {{ $unit->size }} m²
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            {{ $unit->status === 'for_rent' ? 'bg-green-100 text-green-800' :
                            ($unit->status === 'for_sale' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                        {{ __(ucwords(str_replace('_', ' ', $unit->status))) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                    {{ $unit->price ? '€' . number_format($unit->price, 0) : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('admin.units.utilities.index', $unit) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 mr-2">{{ __('Utils') }}</a>
                                                    <a href="{{ route('admin.units.edit', $unit) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 mr-2">{{ __('Edit') }}</a>
                                                    <form action="{{ route('admin.units.destroy', $unit) }}" method="POST"
                                                        class="inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                                    </form>
                                                </td>
                                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $units->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>