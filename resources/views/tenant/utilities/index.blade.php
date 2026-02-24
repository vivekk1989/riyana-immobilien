<x-tenant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Utilities (Nebenkosten)') }}
        </h2>
    </x-slot>

    <div class="mb-6">

        <!-- Unit Info & Switcher & Year Filter -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold">{{ __('My Unit') }}: {{ $unit->unit_number }}</h3>
                    <p class="text-sm text-gray-500">{{ $unit->property->address }}</p>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <!-- Unit Switcher -->
                    @if($allUnits->count() > 1)
                        <form method="GET" action="{{ route('tenant.utilities.index') }}" class="flex items-center">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <select name="unit_id" onchange="this.form.submit()"
                                class="text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($allUnits as $u)
                                    <option value="{{ $u->id }}" {{ $unit->id == $u->id ? 'selected' : '' }}>
                                        {{ $u->property->address }} - {{ $u->unit_number }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif

                    <!-- Year Filter -->
                    <form method="GET" action="{{ route('tenant.utilities.index') }}" class="flex items-center">
                        <input type="hidden" name="unit_id" value="{{ $unit->id }}"> <!-- Maintain Unit -->
                        <label for="year" class="mr-2 text-sm font-bold">{{ __('Year') }}:</label>
                        <select name="year" id="year" class="text-sm border-gray-300 rounded-md shadow-sm"
                            onchange="this.form.submit()">
                            @for($y = date('Y'); $y >= 2024; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>

                    <!-- Download Statement -->
                    @if($period && $period->status == 'PUBLISHED' && $period->pdf_path)
                        <a href="{{ Storage::url($period->pdf_path) }}" target="_blank"
                            class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-4 rounded shadow">
                            {{ __('Download Statement') }}
                        </a>
                    @elseif($period && $period->status == 'LOCKED')
                        <span
                            class="text-orange-500 font-bold text-sm border border-orange-200 bg-orange-50 px-3 py-1 rounded">
                            {{ __('Period Finalized') }}
                        </span>
                    @endif

                    <span
                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded font-semibold text-sm">{{ __('Tenant Portal') }}</span>
                </div>
            </div>
        </div>

        <!-- Assigned Categories (Add Entry Buttons) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach($unit->utilityConfigs as $config)
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="font-bold text-lg text-gray-800">{{ $config->category->name }}</h4>
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded {{ $config->category->input_type == 'meter_reading' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $config->category->input_type == 'meter_reading' ? __('Consumption') : __('Fixed Cost') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">
                        {{ __('Price') }}:
                        {{ $config->price_per_unit ? '€' . number_format($config->price_per_unit, 4) : 'N/A' }}
                        {{ $config->calculation_method ? '/ ' . $config->calculation_method : '' }}
                    </p>
                    @if($config->category->input_type == 'meter_reading')
                        @if($period && in_array($period->status, ['LOCKED', 'PUBLISHED']))
                             <button disabled
                                class="block w-full text-center bg-gray-300 text-gray-500 py-2 rounded cursor-not-allowed">
                                {{ __('Period Finalized') }}
                            </button>
                        @else
                            <a href="{{ route('tenant.utilities.create', $config) }}"
                                class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                                {{ __('Enter Reading') }}
                            </a>
                        @endif
                    @else
                        <button disabled
                            class="block w-full text-center bg-gray-300 text-gray-500 py-2 rounded cursor-not-allowed">
                            {{ __('Managed by Admin') }}
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- History Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-4">{{ __('History') }}</h3>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Category') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Reading / Value') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Est. Cost') }}
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Proof') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($entries as $entry)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $entry->date->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $entry->category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                        {{ number_format($entry->value, 2) }}
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('No entries recorded yet.') }}
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

</x-tenant-layout>