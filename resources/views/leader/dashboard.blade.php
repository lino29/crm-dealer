<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Leader Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        
        {{-- ===== STATS GRID ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $statItems = [
                    [
                        'label' => 'Active Customers',
                        'value' => $stats['total_customers'],
                        'bg' => 'bg-emerald-50 text-emerald-600',
                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                    ],
                    [
                        'label' => 'Total Vehicles',
                        'value' => $stats['total_vehicles'],
                        'bg' => 'bg-indigo-50 text-indigo-600',
                        'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'
                    ],
                    [
                        'label' => 'Services This Month',
                        'value' => $stats['services_this_month'],
                        'bg' => 'bg-blue-50 text-blue-600',
                        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
                    ],
                    [
                        'label' => 'Total Services',
                        'value' => $stats['total_services'],
                        'bg' => 'bg-slate-50 text-slate-600',
                        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
                    ],
                    [
                        'label' => 'Schedules Pending',
                        'value' => $stats['pending_schedules'],
                        'bg' => 'bg-amber-50 text-amber-600',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                    ],
                    [
                        'label' => 'WhatsApp Sent',
                        'value' => $stats['notifications_sent'],
                        'bg' => 'bg-teal-50 text-teal-600',
                        'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'
                    ],
                    [
                        'label' => 'Total Card Scans',
                        'value' => $stats['total_scans'],
                        'bg' => 'bg-cyan-50 text-cyan-600',
                        'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'
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

        {{-- ===== CHARTS ROW ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm border border-slate-200 rounded-2xl p-5 flex flex-col">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    Service Trend (Last 6 Months)
                </h3>
                <div class="relative h-64 flex-1">
                    <canvas id="serviceChart"></canvas>
                </div>
            </div>
            <div class="bg-white shadow-sm border border-slate-200 rounded-2xl p-5 flex flex-col">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Notification Status Breakdown
                </h3>
                <div class="relative h-64 flex-1 flex justify-center items-center">
                    <canvas id="notificationChart"></canvas>
                </div>
            </div>
        </div>

        {{-- ===== QUICK ACTIONS (REPORTS) ===== --}}
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">View Reports</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $reportLinks = [
                        ['route' => 'leader.reports.customers', 'bg' => 'hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-100', 'color' => 'text-slate-400 group-hover:text-emerald-500', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Customers'],
                        ['route' => 'leader.reports.vehicles', 'bg' => 'hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-100', 'color' => 'text-slate-400 group-hover:text-indigo-500', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'label' => 'Vehicles'],
                        ['route' => 'leader.reports.service-histories', 'bg' => 'hover:bg-blue-50 hover:text-blue-600 hover:border-blue-100', 'color' => 'text-slate-400 group-hover:text-blue-500', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'label' => 'Services'],
                        ['route' => 'leader.reports.service-schedules', 'bg' => 'hover:bg-amber-50 hover:text-amber-600 hover:border-amber-100', 'color' => 'text-slate-400 group-hover:text-amber-500', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Schedules'],
                        ['route' => 'leader.reports.whatsapp-notifications', 'bg' => 'hover:bg-teal-50 hover:text-teal-600 hover:border-teal-100', 'color' => 'text-slate-400 group-hover:text-teal-500', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label' => 'WhatsApp'],
                        ['route' => 'leader.reports.scan-logs', 'bg' => 'hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-100', 'color' => 'text-slate-400 group-hover:text-cyan-500', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'label' => 'Scan Logs']
                    ];
                @endphp
                @foreach($reportLinks as $link)
                <a href="{{ route($link['route']) }}" class="group flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-100 transition duration-150 {{ $link['bg'] }}">
                    <svg class="w-7 h-7 mb-2 transition-colors duration-150 {{ $link['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"></path></svg>
                    <span class="text-xs font-bold text-center text-slate-700 group-hover:text-current">{{ $link['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        // Service Trend Chart
        const serviceCtx = document.getElementById('serviceChart').getContext('2d');
        new Chart(serviceCtx, {
            type: 'bar',
            data: {
                labels: @json($stats['chart_services']['labels']),
                datasets: [{
                    label: 'Services',
                    data: @json($stats['chart_services']['data']),
                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 0,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1 },
                        grid: { borderDash: [4, 4] }
                    },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Notification Status Chart
        const notifCtx = document.getElementById('notificationChart').getContext('2d');
        new Chart(notifCtx, {
            type: 'doughnut',
            data: {
                labels: @json($stats['chart_notifications']['labels']),
                datasets: [{
                    data: @json($stats['chart_notifications']['data']),
                    backgroundColor: ['rgb(16, 185, 129)', 'rgb(244, 63, 94)', 'rgb(245, 158, 11)'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 20, font: { weight: 'bold' } }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
