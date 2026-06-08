<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customers') }}
            </h2>
            <a href="{{ route('admin.customers.create') }}" class="px-4 py-2 bg-green-500 text-white rounded">Add Customer</a>
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
                                <th class="border-b py-2 px-4">Name</th>
                                <th class="border-b py-2 px-4">Phone</th>
                                <th class="border-b py-2 px-4">Dealer</th>
                                <th class="border-b py-2 px-4">Status</th>
                                <th class="border-b py-2 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td class="border-b py-2 px-4">{{ $customer->customer_name }}</td>
                                <td class="border-b py-2 px-4">{{ $customer->phone }}</td>
                                <td class="border-b py-2 px-4">{{ $customer->dealer->dealer_name ?? '-' }}</td>
                                <td class="border-b py-2 px-4">
                                    <span class="px-2 py-1 rounded {{ $customer->status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td class="border-b py-2 px-4 flex space-x-2">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-indigo-500 hover:underline">Detail</a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="text-blue-500 hover:underline">Edit</a>
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Set to inactive?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Inactive</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
