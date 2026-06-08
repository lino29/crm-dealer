<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Schedule Report') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <select name="status" class="rounded border-gray-300"><option value="">All Status</option><option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option><option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completed</option><option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option></select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded border-gray-300">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded border-gray-300">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
                </form>
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50"><tr><th class="px-4 py-2">Scheduled</th><th class="px-4 py-2">Customer</th><th class="px-4 py-2">Vehicle</th><th class="px-4 py-2">Status</th></tr></thead>
                    <tbody>
                    @forelse($schedules as $s)
                    <tr class="border-b"><td class="px-4 py-2">{{ $s->scheduled_date->format('d M Y') }}</td><td class="px-4 py-2">{{ $s->vehicle->customer->customer_name ?? '-' }}</td><td class="px-4 py-2">{{ $s->vehicle->police_number ?? '-' }}</td><td class="px-4 py-2"><span class="px-2 py-1 rounded text-xs {{ $s->status==='completed'?'bg-green-200 text-green-800':($s->status==='cancelled'?'bg-red-200 text-red-800':'bg-yellow-200 text-yellow-800') }}">{{ ucfirst($s->status) }}</span></td></tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-4 text-center text-gray-500 italic">No data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
