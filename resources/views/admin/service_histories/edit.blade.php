<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Service History') }}: {{ $vehicle->police_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.service_histories.update', $history) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->vehicle_id }}">

                        <div class="mb-4 flex space-x-4">
                            <div class="w-1/2">
                                <label for="service_date" class="block text-gray-700 text-sm font-bold mb-2">Service Date</label>
                                <input type="date" name="service_date" id="service_date" value="{{ old('service_date', $history->service_date->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" required>
                                @error('service_date') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="w-1/2">
                                <label for="mileage" class="block text-gray-700 text-sm font-bold mb-2">Mileage (km)</label>
                                <input type="number" name="mileage" id="mileage" value="{{ old('mileage', $history->mileage) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" min="0">
                                @error('mileage') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="service_type" class="block text-gray-700 text-sm font-bold mb-2">Service Type</label>
                            <input type="text" name="service_type" id="service_type" value="{{ old('service_type', $history->service_type) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" placeholder="e.g. Oil Change">
                        </div>

                        <div class="mb-4">
                            <label for="complaint" class="block text-gray-700 text-sm font-bold mb-2">Complaint / Keluhan</label>
                            <textarea name="complaint" id="complaint" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">{{ old('complaint', $history->complaint) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="service_action" class="block text-gray-700 text-sm font-bold mb-2">Service Action / Tindakan</label>
                            <textarea name="service_action" id="service_action" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">{{ old('service_action', $history->service_action) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" id="description" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">{{ old('description', $history->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="service_note" class="block text-gray-700 text-sm font-bold mb-2">Service Note / Catatan</label>
                            <textarea name="service_note" id="service_note" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">{{ old('service_note', $history->service_note) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="next_service_date" class="block text-gray-700 text-sm font-bold mb-2">Next Service Date</label>
                            <input type="date" name="next_service_date" id="next_service_date" value="{{ old('next_service_date', $history->next_service_date?->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                            <a href="{{ route('admin.vehicles.service_histories.index', $vehicle) }}" class="text-sm text-blue-500 hover:text-blue-800">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
