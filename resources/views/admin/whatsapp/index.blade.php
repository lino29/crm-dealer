<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('WhatsApp Reminders') }}</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">{{ session('error') }}</div>@endif

            {{-- Due schedules --}}
            @if($dueSchedules->count() > 0)
            <div class="bg-yellow-50 border border-yellow-300 shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-yellow-800">⏰ Jadwal Perlu Diingatkan ({{ $dueSchedules->count() }})</h3>
                <table class="w-full text-left">
                    <thead><tr>
                        <th class="px-4 py-2">Customer</th><th class="px-4 py-2">Vehicle</th><th class="px-4 py-2">Jadwal</th><th class="px-4 py-2">Action</th>
                    </tr></thead>
                    <tbody>
                    @foreach($dueSchedules as $sched)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $sched->vehicle->customer->customer_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $sched->vehicle->police_number ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $sched->scheduled_date->format('d M Y') }}</td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $sched->schedule_id }}">
                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Send Reminder</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Notification history --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Notification History</h3>
                <form method="GET" class="flex flex-wrap gap-4 items-end mb-4">
                    <select name="status" class="rounded border-gray-300">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sent" {{ request('status')==='sent' ? 'selected' : '' }}>Sent</option>
                        <option value="failed" {{ request('status')==='failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded border-gray-300">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded border-gray-300">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
                </form>
                <table class="w-full text-left">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-4 py-2">Date</th><th class="px-4 py-2">Customer</th><th class="px-4 py-2">Phone</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Action</th>
                    </tr></thead>
                    <tbody>
                    @forelse($notifications as $n)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $n->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $n->customer->customer_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $n->phone }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $n->send_status === 'sent' ? 'bg-green-200 text-green-800' : ($n->send_status === 'failed' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                    {{ ucfirst($n->send_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if($n->send_status === 'failed')
                                <form method="POST" action="{{ route('admin.whatsapp.retry', $n) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:underline text-sm">Retry</button>
                                </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500 italic">No notifications found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $notifications->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
