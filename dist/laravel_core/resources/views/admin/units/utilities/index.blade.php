<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Utilities for Unit') }} {{ $unit->unit_number }} ({{ $unit->property->address }})
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <!-- Year Filter -->
                <form method="GET" action="{{ route('admin.units.utilities.index', $unit) }}" class="flex items-center">
                    <label for="year" class="mr-2 font-bold">{{ __('Year') }}:</label>
                    <select name="year" id="year" class="border-gray-300 rounded-md shadow-sm"
                        onchange="this.form.submit()">
                        @for($y = date('Y') + 1; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>

                <!-- Period Status & Actions -->
                <div class="flex items-center gap-4">
                    <div class="text-sm">
                        <span class="font-bold">{{ __('Status') }}:</span>
                        <span
                            class="@if($period->status == 'OPEN') text-green-600 @elseif($period->status == 'LOCKED') text-orange-600 @else text-blue-600 @endif font-bold">
                            {{ $period->status }}
                        </span>
                    </div>

                    @if($period->status == 'OPEN')
                        <form action="{{ route('admin.units.utilities.finalize', ['unit' => $unit, 'year' => $year]) }}"
                            method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('{{ __('Are you sure? This will lock the period and generate the PDF.') }}')">
                                {{ __('Finalize Year') }}
                            </button>
                        </form>
                    @elseif(in_array($period->status, ['LOCKED', 'PUBLISHED']))
                        @if($period->status == 'LOCKED')
                            <form action="{{ route('admin.units.utilities.publish', ['unit' => $unit, 'year' => $year]) }}"
                                method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('{{ __('Send email to tenant?') }}')">
                                    {{ __('Publish & Send') }}
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.units.utilities.unlock', ['unit' => $unit, 'year' => $year]) }}"
                            method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('{{ __('Are you sure? This will delete the PDF and allow editing again.') }}')">
                                {{ __('Unlock/Revert') }}
                            </button>
                        </form>
                        @if($period->pdf_path)
                            <a href="{{ Storage::url($period->pdf_path) }}" target="_blank"
                                class="text-blue-600 hover:underline text-sm ml-2">
                                {{ __('View PDF') }}
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            @if($period->status == 'OPEN')
                <h3 class="text-lg font-bold mb-4">{{ __('Assign New Utility') }}</h3>

                @if($availableCategories->isEmpty())
                    <p class="text-gray-500">
                        {{ __('All available utility categories have been assigned to this unit.') }}
                    </p>
                @else
                    <form action="{{ route('admin.units.utilities.store', $unit) }}" method="POST"
                        class="flex flex-wrap items-end gap-4">
                        @csrf
                        <input type="hidden" name="year" value="{{ $year }}">
                        <div class="w-full sm:w-auto">
                            <label for="utility_category_id"
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('Category') }}:</label>
                            <select name="utility_category_id" id="utility_category_id"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                @foreach($availableCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->input_type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-auto">
                            <label for="price_per_unit"
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('Price / Unit') }}
                                (€):</label>
                            <input type="number" step="0.0001" name="price_per_unit" id="price_per_unit"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="e.g. 0.50">
                        </div>

                        <div class="w-full sm:w-auto">
                            <label for="calculation_method"
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('Calc. Method (Opt)') }}:</label>
                            <input type="text" name="calculation_method" id="calculation_method"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="e.g. per m²">
                        </div>

                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('Assign') }}
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-bold mb-4">{{ __('Assigned Utilities') }}</h3>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Category') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Type') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Price / Unit') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Calc. Method (Opt)') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($unit->utilityConfigs as $config)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $config->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                {{ $config->category->input_type == 'meter_reading' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $config->category->input_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $config->price_per_unit ? '€' . number_format($config->price_per_unit, 4) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $config->calculation_method ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.units.utilities.entries', ['unit' => $unit, 'config' => $config]) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">{{ __('Entries') }}</a>
                                <a href="{{ route('admin.units.utilities.edit', ['unit' => $unit, 'config' => $config]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Edit') }}</a>
                                <form
                                    action="{{ route('admin.units.utilities.destroy', ['unit' => $unit, 'config' => $config]) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900">{{ __('Remove') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                {{ __('No utilities assigned yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                <a href="{{ route('admin.units.index') }}"
                    class="text-blue-500 hover:text-blue-800">{{ __('Back to Units') }}</a>
            </div>
        </div>

</x-admin-layout>