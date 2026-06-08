<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.reports.services') }}" method="GET" class="flex flex-wrap items-end gap-4">
                        <div>
                            <label for="dealer_id" class="block text-sm font-medium text-gray-700">Dealer</label>
                            <select name="dealer_id" id="dealer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Dealers</option>
                                @foreach($dealers as $dealer)
                                    <option value="{{ $dealer->dealer_id }}" {{ request('dealer_id') == $dealer->dealer_id ? 'selected' : '' }}>{{ $dealer->dealer_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                        </div>
                        <div class="ml-auto flex space-x-2">
                            <button type="submit" name="export" value="pdf" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Export PDF
                            </button>
                            <button type="submit" name="export" value="excel" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Export Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 px-4">Date</th>
                                <th class="border-b py-2 px-4">Vehicle</th>
                                <th class="border-b py-2 px-4">Customer</th>
                                <th class="border-b py-2 px-4">Dealer</th>
                                <th class="border-b py-2 px-4">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                            <tr>
                                <td class="border-b py-2 px-4">{{ $service->service_date->format('d M Y') }}</td>
                                <td class="border-b py-2 px-4">{{ $service->vehicle->police_number }}</td>
                                <td class="border-b py-2 px-4">{{ $service->vehicle->customer->customer_name }}</td>
                                <td class="border-b py-2 px-4">{{ $service->vehicle->dealer->dealer_name ?? '-' }}</td>
                                <td class="border-b py-2 px-4">{{ $service->service_type }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border-b py-4 px-4 text-center text-gray-500 italic">No services found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
