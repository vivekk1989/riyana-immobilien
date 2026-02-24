<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Utility Categories') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-bold">{{ __('List of Utility Categories') }}</h3>
                <a href="{{ route('admin.utilities.categories.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Add New Category') }}
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Name') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Input Type') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $category->input_type == 'meter_reading' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $category->input_type == 'meter_reading' ? __('Meter Reading') : __('Fixed Cost') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.utilities.categories.edit', $category) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Edit') }}</a>
                                <form action="{{ route('admin.utilities.categories.destroy', $category) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('{{ __('Are you sure? This will delete all associated configs and entries!') }}');">
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
    </div>
</x-admin-layout>