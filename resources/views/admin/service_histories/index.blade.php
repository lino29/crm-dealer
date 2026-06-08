<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Service History for') }}: {{ $vehicle->police_number }} ({{ $vehicle->brand }} {{ $vehicle->model }})
            </h2>
            <a href="{{ route('admin.vehicles.service_histories.create', $vehicle) }}" class="px-4 py-2 bg-green-500 text-white rounded">Add Service History</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 px-4">Date</th>
                                <th class="border-b py-2 px-4">Mileage</th>
                                <th class="border-b py-2 px-4">Type</th>
                                <th class="border-b py-2 px-4">Description</th>
                                <th class="border-b py-2 px-4">Next Service Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $history)
                            <tr>
                                <td class="border-b py-2 px-4">{{ $history->service_date->format('d M Y') }}</td>
                                <td class="border-b py-2 px-4">{{ $history->mileage ? number_format($history->mileage) . ' km' : '-' }}</td>
                                <td class="border-b py-2 px-4">{{ $history->service_type ?? '-' }}</td>
                                <td class="border-b py-2 px-4">{{ $history->description ?? '-' }}</td>
                                <td class="border-b py-2 px-4">{{ $history->next_service_date ? $history->next_service_date->format('d M Y') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border-b py-4 px-4 text-center text-gray-500 italic">No service history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-8">
                        <a href="{{ route('admin.customers.show', $vehicle->customer_id) }}" class="text-blue-500 hover:underline">Back to Customer Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
