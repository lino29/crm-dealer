<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Dealers -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Dealers</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_dealers'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Customers</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_customers'] }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <!-- Total Vehicles -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Vehicles</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_vehicles'] }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                </div>

                <!-- Pending Schedules -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Pending Sched.</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_schedules'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                
                <!-- Today Services -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Services Today</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['today_services'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>

                <!-- Today Scans -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Scans Today</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['today_scans'] }}</p>
                    </div>
                    <div class="p-3 bg-cyan-50 rounded-lg">
                        <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                </div>

                <!-- Failed Notifications -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Failed WA</p>
                        <p class="text-3xl font-bold text-red-600">{{ $stats['failed_notifications'] }}</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>

                <!-- Completed Schedules -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Completed Sched.</p>
                        <p class="text-3xl font-bold text-teal-600">{{ $stats['completed_schedules'] }}</p>
                    </div>
                    <div class="p-3 bg-teal-50 rounded-lg">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Due Schedules Table --}}
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Upcoming Schedules
                        </h3>
                        <a href="{{ route('admin.service_schedules.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        @if($stats['due_schedules']->count() > 0)
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-white border-b border-gray-100 text-gray-500">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Customer</th>
                                    <th class="px-6 py-3 font-medium">Vehicle</th>
                                    <th class="px-6 py-3 font-medium">Date</th>
                                    <th class="px-6 py-3 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @foreach($stats['due_schedules'] as $sched)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-800 font-medium">{{ $sched->vehicle->customer->customer_name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $sched->vehicle->police_number ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $sched->scheduled_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">{{ ucfirst($sched->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="p-6 text-center text-gray-500">No upcoming schedules in the next 7 days.</div>
                        @endif
                    </div>
                </div>

                {{-- Recent Scans Table --}}
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Recent Scans
                        </h3>
                        <a href="{{ route('admin.audit.scan_logs') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        @if($stats['recent_scans']->count() > 0)
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-white border-b border-gray-100 text-gray-500">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Time</th>
                                    <th class="px-6 py-3 font-medium">Status</th>
                                    <th class="px-6 py-3 font-medium">Customer</th>
                                    <th class="px-6 py-3 font-medium">Scanned By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @foreach($stats['recent_scans'] as $scan)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-600">{{ $scan->scanned_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $scan->status==='success'?'bg-green-100 text-green-800':'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($scan->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 font-medium">{{ $scan->memberCard->customer->customer_name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $scan->scanner->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="p-6 text-center text-gray-500">No recent scans found.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Links Grid --}}
            <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <a href="{{ route('admin.dealers.index') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition border border-transparent hover:border-blue-100">
                        <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Dealers</span>
                    </a>
                    
                    <a href="{{ route('admin.customers.index') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-green-50 hover:text-green-600 transition border border-transparent hover:border-green-100">
                        <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-sm font-medium text-center text-gray-700 group-hover:text-green-700">Customers</span>
                    </a>

                    <a href="{{ route('admin.scan.index') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-cyan-50 hover:text-cyan-600 transition border border-transparent hover:border-cyan-100">
                        <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span class="text-sm font-medium text-center text-gray-700 group-hover:text-cyan-700">Scan QR</span>
                    </a>

                    <a href="{{ route('admin.service_schedules.index') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition border border-transparent hover:border-yellow-100">
                        <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-medium text-center text-gray-700 group-hover:text-yellow-700">Schedules</span>
                    </a>

                    <a href="{{ route('admin.whatsapp.index') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 transition border border-transparent hover:border-emerald-100">
                        <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">WhatsApp</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-orange-50 hover:text-orange-600 transition border border-transparent hover:border-orange-100">
                        <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="text-sm font-medium text-center text-gray-700 group-hover:text-orange-700">Users</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
