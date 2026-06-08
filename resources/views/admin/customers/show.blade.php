<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Detail') }}: {{ $customer->customer_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">Profile</h3>
                        <p><strong>Name:</strong> {{ $customer->customer_name }}</p>
                        <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                        <p><strong>Gender:</strong> {{ ucfirst($customer->gender) }}</p>
                        <p><strong>Birth Date:</strong> {{ $customer->birth_date->format('d M Y') }}</p>
                        <p><strong>Address:</strong> {{ $customer->address }}</p>
                        <p><strong>Dealer:</strong> {{ $customer->dealer->dealer_name ?? '-' }}</p>
                        <p><strong>USK Month:</strong> {{ $customer->usk_month }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($customer->status) }}</p>
                    </div>

                    <!-- Member Card Section -->
                    <div class="mt-8 border-t pt-4">
                        <h3 class="text-lg font-semibold mb-2">Member Card</h3>
                        @if($customer->memberCard)
                            <p><strong>Member Code:</strong> {{ $customer->memberCard->member_code }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($customer->memberCard->status) }}</p>
                            <p><strong>Print Count:</strong> {{ $customer->memberCard->print_count }}</p>
                            <div class="mt-2 flex space-x-2">
                                <a href="{{ route('admin.member_cards.preview', $customer) }}" class="px-4 py-2 bg-blue-500 text-white rounded text-sm">Preview Card</a>
                                <a href="{{ route('admin.member_cards.print', $customer) }}" target="_blank" class="px-4 py-2 bg-gray-500 text-white rounded text-sm">Print Card</a>
                            </div>
                        @else
                            <p class="text-gray-500 italic mb-2">No member card generated yet.</p>
                            <form action="{{ route('admin.member_cards.generate', $customer) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded text-sm">Generate Member Card</button>
                            </form>
                        @endif
                    </div>

                    <!-- Vehicles List -->
                    <div class="mt-8 border-t pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Vehicles</h3>
                            <a href="{{ route('admin.vehicles.create', ['customer_id' => $customer->customer_id]) }}" class="px-4 py-2 bg-green-500 text-white rounded text-sm">Add Vehicle</a>
                        </div>
                        
                        @if($customer->vehicles->count() > 0)
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr>
                                        <th class="border-b py-2 px-4">Police Number</th>
                                        <th class="border-b py-2 px-4">Brand/Model</th>
                                        <th class="border-b py-2 px-4">Color</th>
                                        <th class="border-b py-2 px-4">Purchase Date</th>
                                        <th class="border-b py-2 px-4">Status</th>
                                        <th class="border-b py-2 px-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->vehicles as $vehicle)
                                    <tr>
                                        <td class="border-b py-2 px-4">{{ $vehicle->police_number }}</td>
                                        <td class="border-b py-2 px-4">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                        <td class="border-b py-2 px-4">{{ $vehicle->color }}</td>
                                        <td class="border-b py-2 px-4">{{ $vehicle->purchase_date ? $vehicle->purchase_date->format('d M Y') : '-' }}</td>
                                        <td class="border-b py-2 px-4">
                                            <span class="px-2 py-1 rounded {{ $vehicle->status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                                {{ ucfirst($vehicle->status) }}
                                            </span>
                                        </td>
                                        <td class="border-b py-2 px-4 flex space-x-2">
                                            <a href="{{ route('admin.vehicles.service_histories.index', $vehicle) }}" class="text-indigo-500 hover:underline">History</a>
                                            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-blue-500 hover:underline">Edit</a>
                                            <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Set to inactive?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline">Inactive</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500 italic">No vehicles found for this customer.</p>
                        @endif
                    </div>

                    <div class="mt-8 border-t pt-4">
                        <a href="{{ route('admin.customers.index') }}" class="text-blue-500 hover:underline">Back to Customers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
