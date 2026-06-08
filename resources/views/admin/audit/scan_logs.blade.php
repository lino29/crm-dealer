<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 px-4">Time</th>
                                <th class="border-b py-2 px-4">QR Token Scanned</th>
                                <th class="border-b py-2 px-4">Status</th>
                                <th class="border-b py-2 px-4">Customer</th>
                                <th class="border-b py-2 px-4">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td class="border-b py-2 px-4">{{ $log->scanned_at->format('d M Y H:i:s') }}</td>
                                <td class="border-b py-2 px-4">{{ $log->qr_token_scanned }}</td>
                                <td class="border-b py-2 px-4">
                                    <span class="px-2 py-1 rounded {{ $log->status === 'success' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td class="border-b py-2 px-4">{{ $log->memberCard->customer->customer_name ?? '-' }}</td>
                                <td class="border-b py-2 px-4">{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border-b py-4 px-4 text-center text-gray-500 italic">No scan logs found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
