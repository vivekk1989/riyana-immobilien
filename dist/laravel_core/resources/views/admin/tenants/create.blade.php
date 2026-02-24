<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Tenant') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('admin.tenants.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Name') }}</label>
                    <input id="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text" name="name" value="{{ old('name') }}" required autofocus />
                    @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email') }}</label>
                    <input id="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="email" name="email" value="{{ old('email') }}" required />
                    @error('email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Create Tenant & Send Credentials') }}
                    </button>
                    <a href="{{ route('admin.tenants.index') }}"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>