<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Properties') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="mb-4 flex justify-between items-center">
                <p class="text-gray-600">{{ __('Overview of all properties.') }}</p>
                <a href="{{ route('admin.properties.create') }}" class="btn-primary">
                    + {{ __('New Property') }}
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
                                {{ __('Address') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Type') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Units') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($properties as $property)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-12 w-16 bg-gray-200 rounded overflow-hidden">
                                        @if($property->photos->first())
                                            <img src="{{ Storage::url($property->photos->first()->path) }}" alt="Property"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-xs text-gray-400">
                                                {{ __('No Img') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $property->address }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $property->type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $property->units->count() }} {{ __('Units') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.properties.units.index', $property) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-4">{{ __('View Units') }}</a>
                                    <a href="{{ route('admin.properties.edit', $property) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-2">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.properties.destroy', $property) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>