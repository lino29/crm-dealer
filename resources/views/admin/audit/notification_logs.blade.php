<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Notification Logs') }}</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="flex flex-wrap gap-4 items-end mb-4">
                    <select name="send_status" class="rounded border-gray-300">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('send_status')==='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sent" {{ request('send_status')==='sent' ? 'selected' : '' }}>Sent</option>
                        <option value="failed" {{ request('send_status')==='failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded border-gray-300">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded border-gray-300">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
                    <a href="{{ route('admin.audit.notification_logs') }}" class="px-4 py-2 bg-gray-200 rounded">Reset</a>
                </form>
                <table class="w-full text-left">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-4 py-2">Time</th><th class="px-4 py-2">Customer</th><th class="px-4 py-2">Phone</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Sent By</th><th class="px-4 py-2">Gateway Response</th>
                    </tr></thead>
                    <tbody>
                    @forelse($notifications as $n)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $n->created_at->format('d M Y H:i:s') }}</td>
                            <td class="px-4 py-2">{{ $n->customer->customer_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $n->phone }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs {{ $n->send_status === 'sent' ? 'bg-green-200 text-green-800' : ($n->send_status === 'failed' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                    {{ ucfirst($n->send_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $n->creator->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($n->gateway_response, 50) ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500 italic">No notification logs found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $notifications->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
