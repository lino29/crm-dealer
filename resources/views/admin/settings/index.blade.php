<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('System Settings') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                {{-- Notification Settings --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">📱 Notification Settings</h3>
                    
                    <div class="mb-4">
                        <label for="wa_template" class="block text-gray-700 text-sm font-bold mb-2">WhatsApp Template</label>
                        <p class="text-sm text-gray-500 mb-2">Variables: <code>{customer_name}</code>, <code>{vehicle_info}</code>, <code>{date}</code></p>
                        <textarea name="wa_template" id="wa_template" rows="4" class="w-full rounded border-gray-300" required>{{ old('wa_template', $template) }}</textarea>
                        @error('wa_template') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="reminder_days" class="block text-gray-700 text-sm font-bold mb-2">Reminder Days Before Service</label>
                        <p class="text-sm text-gray-500 mb-2">Number of days before scheduled service date to send reminder (e.g. 3 = H-3)</p>
                        <input type="number" name="reminder_days" id="reminder_days" value="{{ old('reminder_days', $reminderDays) }}" class="w-32 rounded border-gray-300" min="1" max="30" required>
                        @error('reminder_days') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Company Identity --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">🏢 Company Identity</h3>
                    
                    <div class="mb-4">
                        <label for="company_name" class="block text-gray-700 text-sm font-bold mb-2">Company Name</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $companyName) }}" class="w-full rounded border-gray-300">
                    </div>
                    <div class="mb-4">
                        <label for="company_address" class="block text-gray-700 text-sm font-bold mb-2">Company Address</label>
                        <textarea name="company_address" id="company_address" rows="2" class="w-full rounded border-gray-300">{{ old('company_address', $companyAddress) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="company_phone" class="block text-gray-700 text-sm font-bold mb-2">Company Phone/WhatsApp</label>
                        <input type="text" name="company_phone" id="company_phone" value="{{ old('company_phone', $companyPhone) }}" class="w-full rounded border-gray-300">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 font-bold">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
