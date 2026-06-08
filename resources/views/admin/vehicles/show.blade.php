<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vehicle Detail') }}: {{ $vehicle->police_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div><p class="text-sm text-gray-500">Plate Number</p><p class="font-bold">{{ $vehicle->police_number }}</p></div>
                    <div><p class="text-sm text-gray-500">Brand</p><p class="font-bold">{{ $vehicle->brand }}</p></div>
                    <div><p class="text-sm text-gray-500">Model</p><p class="font-bold">{{ $vehicle->model }}</p></div>
                    <div><p class="text-sm text-gray-500">Year</p><p class="font-bold">{{ $vehicle->production_year ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Color</p><p class="font-bold">{{ $vehicle->color ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Status</p><p><span class="px-2 py-1 rounded text-xs {{ $vehicle->status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">{{ ucfirst($vehicle->status) }}</span></p></div>
                    <div><p class="text-sm text-gray-500">Engine Number</p><p class="font-bold">{{ $vehicle->engine_number ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Chassis Number</p><p class="font-bold">{{ $vehicle->chassis_number ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Owner</p><p class="font-bold">
                        @if($vehicle->customer)
                            <a href="{{ route('admin.customers.show', $vehicle->customer) }}" class="text-blue-600 hover:underline">{{ $vehicle->customer->customer_name }}</a>
                        @else - @endif
                    </p></div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Edit Vehicle</a>
                <a href="{{ route('admin.vehicles.service_histories.index', $vehicle) }}" class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-sm">Service History</a>
                <a href="{{ route('admin.vehicles.index') }}" class="px-4 py-2 bg-gray-200 rounded text-sm">Back to List</a>
            </div>
        </div>
    </div>
</x-app-layout>
