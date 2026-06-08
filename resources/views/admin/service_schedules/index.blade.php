<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Schedules') }}
        </h2>
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
                                <th class="border-b py-2 px-4">Scheduled Date</th>
                                <th class="border-b py-2 px-4">Vehicle</th>
                                <th class="border-b py-2 px-4">Customer</th>
                                <th class="border-b py-2 px-4">Status</th>
                                <th class="border-b py-2 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                            <tr>
                                <td class="border-b py-2 px-4">{{ $schedule->scheduled_date->format('d M Y') }}</td>
                                <td class="border-b py-2 px-4">{{ $schedule->vehicle->police_number }} ({{ $schedule->vehicle->brand }})</td>
                                <td class="border-b py-2 px-4">{{ $schedule->vehicle->customer->customer_name }}</td>
                                <td class="border-b py-2 px-4">
                                    <span class="px-2 py-1 rounded {{ $schedule->status === 'completed' ? 'bg-green-200 text-green-800' : ($schedule->status === 'cancelled' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </td>
                                <td class="border-b py-2 px-4">
                                    @if($schedule->status === 'pending')
                                    <form action="{{ route('admin.service_schedules.complete', $schedule) }}" method="POST" onsubmit="return confirm('Mark as completed?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-500 hover:underline">Complete</button>
                                    </form>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border-b py-4 px-4 text-center text-gray-500 italic">No upcoming service schedules found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
