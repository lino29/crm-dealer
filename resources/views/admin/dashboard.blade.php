<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">

        {{-- ===== WHATSAPP CONNECTION STATUS ===== --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4 transition hover:shadow-md">
            <div class="flex items-center space-x-4">
                <div class="p-3.5 {{ $waConnection['connected'] ? 'bg-emerald-50 text-emerald-600' : ($waConnection['status'] === 'dummy' ? 'bg-blue-50 text-blue-600' : 'bg-rose-50 text-rose-600') }} rounded-xl shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">WhatsApp Gateway Status</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $waConnection['message'] }}</p>
                </div>
            </div>
            <div class="shrink-0">
                @if($waConnection['connected'])
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 shadow-sm border border-emerald-200">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Connected
                    </span>
                @elseif($waConnection['status'] === 'dummy')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 shadow-sm border border-blue-200">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-blue-500"></span>
                        Dummy Mode
                    </span>
                @elseif($waConnection['status'] === 'qr_ready')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 animate-bounce shadow-sm border border-amber-200">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-amber-500 animate-ping"></span>
                        Scan QR Code
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 shadow-sm border border-rose-200">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-rose-500"></span>
                        Disconnected
                    </span>
                @endif
            </div>
        </div>

        {{-- ===== STATS GRID ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $statItems = [
                    [
                        'label' => 'Total Dealers',
                        'value' => $stats['total_dealers'],
                        'bg' => 'bg-indigo-50 text-indigo-600',
                        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                    ],
                    [
                        'label' => 'Total Customers',
                        'value' => $stats['total_customers'],
                        'bg' => 'bg-emerald-50 text-emerald-600',
                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                    ],
                    [
                        'label' => 'Total Vehicles',
                        'value' => $stats['total_vehicles'],
                        'bg' => 'bg-sky-50 text-sky-600',
                        'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'
                    ],
                    [
                        'label' => 'Schedules Pending',
                        'value' => $stats['pending_schedules'],
                        'bg' => 'bg-amber-50 text-amber-600',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                    ],
                    [
                        'label' => 'Services Today',
                        'value' => $stats['today_services'],
                        'bg' => 'bg-purple-50 text-purple-600',
                        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
                    ],
                    [
                        'label' => 'Card Scans Today',
                        'value' => $stats['today_scans'],
                        'bg' => 'bg-cyan-50 text-cyan-600',
                        'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'
                    ],
                    [
                        'label' => 'Failed WA Alerts',
                        'value' => $stats['failed_notifications'],
                        'bg' => 'bg-rose-50 text-rose-600',
                        'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                    ],
                    [
                        'label' => 'Completed Sched.',
                        'value' => $stats['completed_schedules'],
                        'bg' => 'bg-teal-50 text-teal-600',
                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                    ]
                ];
            @endphp

            @foreach($statItems as $stat)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider truncate">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stat['value'] }}</p>
                </div>
                <div class="p-3.5 {{ $stat['bg'] }} rounded-xl shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path></svg>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ===== TABLES ROW ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Due Schedules Table --}}
            <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Upcoming Schedules
                    </h3>
                    <a href="{{ route('admin.service_schedules.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">View All</a>
                </div>
                <div class="p-0 overflow-x-auto flex-1">
                    @if($stats['due_schedules']->count() > 0)
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">Vehicle</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @foreach($stats['due_schedules'] as $sched)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3.5 text-slate-800 font-semibold">
                                    @if($sched->vehicle?->customer)
                                        <a href="{{ route('admin.customers.show', $sched->vehicle->customer) }}" class="text-indigo-600 hover:underline">
                                            {{ $sched->vehicle->customer->customer_name }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-mono">{{ $sched->vehicle->police_number ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $sched->scheduled_date->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ $sched->status }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="p-8 text-center text-slate-400 italic text-xs">No upcoming schedules in the next 7 days.</div>
                    @endif
                </div>
            </div>

            {{-- Recent Scans Table --}}
            <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Recent Scans
                    </h3>
                    <a href="{{ route('admin.audit.scan_logs') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">View All</a>
                </div>
                <div class="p-0 overflow-x-auto flex-1">
                    @if($stats['recent_scans']->count() > 0)
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Time</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">Scanner</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @foreach($stats['recent_scans'] as $scan)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3.5 text-slate-500">{{ $scan->scanned_at->format('d M Y H:i') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $scan->status === 'success' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $scan->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-800 font-semibold">
                                    @if($scan->memberCard?->customer)
                                        <a href="{{ route('admin.customers.show', $scan->memberCard->customer) }}" class="text-indigo-600 hover:underline">
                                            {{ $scan->memberCard->customer->customer_name }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $scan->scanner->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="p-8 text-center text-slate-400 italic text-xs">No recent scans found.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== QUICK ACTIONS ===== --}}
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Quick Actions</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @php
                    $actions = [
                        ['route' => 'admin.dealers.index', 'bg' => 'hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-100', 'color' => 'text-slate-400 group-hover:text-indigo-500', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Dealers'],
                        ['route' => 'admin.customers.index', 'bg' => 'hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-100', 'color' => 'text-slate-400 group-hover:text-emerald-500', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Customers'],
                        ['route' => 'admin.scan.index', 'bg' => 'hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-100', 'color' => 'text-slate-400 group-hover:text-cyan-500', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'label' => 'Scan QR'],
                        ['route' => 'admin.service_schedules.index', 'bg' => 'hover:bg-amber-50 hover:text-amber-600 hover:border-amber-100', 'color' => 'text-slate-400 group-hover:text-amber-500', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Schedules'],
                        ['route' => 'admin.whatsapp.index', 'bg' => 'hover:bg-teal-50 hover:text-teal-600 hover:border-teal-100', 'color' => 'text-slate-400 group-hover:text-teal-500', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label' => 'WhatsApp'],
                        ['route' => 'admin.users.index', 'bg' => 'hover:bg-rose-50 hover:text-rose-600 hover:border-rose-100', 'color' => 'text-slate-400 group-hover:text-rose-500', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Users']
                    ];
                @endphp
                @foreach($actions as $action)
                <a href="{{ route($action['route']) }}" class="group flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-100 transition duration-150 {{ $action['bg'] }}">
                    <svg class="w-7 h-7 mb-2 transition-colors duration-150 {{ $action['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"></path></svg>
                    <span class="text-xs font-bold text-center text-slate-700 group-hover:text-current">{{ $action['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
