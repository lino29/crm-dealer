<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Scan Log Report') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <select name="status" class="rounded border-gray-300"><option value="">All</option><option value="success" {{ request('status')==='success'?'selected':'' }}>Success</option><option value="failed" {{ request('status')==='failed'?'selected':'' }}>Failed</option><option value="invalid" {{ request('status')==='invalid'?'selected':'' }}>Invalid</option></select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded border-gray-300">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded border-gray-300">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
                </form>
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50"><tr><th class="px-4 py-2">Time</th><th class="px-4 py-2">Token</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Customer</th><th class="px-4 py-2">Scanned By</th></tr></thead>
                    <tbody>
                    @forelse($logs as $log)
                    <tr class="border-b"><td class="px-4 py-2">{{ $log->scanned_at->format('d M Y H:i:s') }}</td><td class="px-4 py-2 text-xs">{{ \Illuminate\Support\Str::limit($log->qr_token_scanned, 20) ?? '-' }}</td><td class="px-4 py-2"><span class="px-2 py-1 rounded text-xs {{ $log->status==='success'?'bg-green-200 text-green-800':'bg-red-200 text-red-800' }}">{{ ucfirst($log->status) }}</span></td><td class="px-4 py-2">{{ $log->memberCard->customer->customer_name ?? '-' }}</td><td class="px-4 py-2">{{ $log->scanner->name ?? '-' }}</td></tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500 italic">No data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
