<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Vehicles') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Plate Number</th>
                            <th class="px-4 py-3">Brand</th>
                            <th class="px-4 py-3">Model</th>
                            <th class="px-4 py-3">Year</th>
                            <th class="px-4 py-3">Owner</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $vehicle)
                        <tr class="border-b">
                            <td class="px-4 py-3 font-medium">{{ $vehicle->police_number }}</td>
                            <td class="px-4 py-3">{{ $vehicle->brand }}</td>
                            <td class="px-4 py-3">{{ $vehicle->model }}</td>
                            <td class="px-4 py-3">{{ $vehicle->production_year ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($vehicle->customer)
                                    <a href="{{ route('admin.customers.show', $vehicle->customer) }}" class="text-blue-600 hover:underline">{{ $vehicle->customer->customer_name }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $vehicle->status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ ucfirst($vehicle->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                <a href="{{ route('admin.vehicles.service_histories.index', $vehicle) }}" class="text-indigo-600 hover:underline text-sm">Service History</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-4 py-4 text-center text-gray-500 italic">No vehicles found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">{{ $vehicles->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
