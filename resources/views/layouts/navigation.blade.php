@php
    $role = Auth::user()->role->role_name;
    $dashboardRoute = $role === 'admin' ? route('admin.dashboard') : route('leader.dashboard');
    $isDashboardActive = request()->routeIs('admin.dashboard') || request()->routeIs('leader.dashboard');
@endphp

{{--
    Sidebar: fixed on large screens, slide-over on mobile.
    Uses Alpine.js `sidebarOpen` from parent x-data (app.blade.php).
--}}
<aside
    class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 bg-slate-900 text-white transform transition-transform duration-300 ease-in-out shrink-0
           lg:relative lg:translate-x-0 lg:z-auto"
    :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'"
>

    {{-- ===== BRANDING ===== --}}
    <div class="flex items-center h-16 px-5 border-b border-slate-700 shrink-0 gap-3">
        <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center shrink-0 shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="font-bold text-sm text-white leading-none truncate">CRM Dealer</p>
                <p class="text-xs text-slate-400 capitalize mt-0.5">{{ $role }}</p>
            </div>
        </a>
        {{-- Close button (mobile) --}}
        <button @click="sidebarOpen = false" class="ml-auto p-1 rounded text-slate-400 hover:text-white lg:hidden focus:outline-none" aria-label="Close sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ===== NAVIGATION ===== --}}
    <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-6">

        {{-- Dashboard --}}
        <div>
            <a href="{{ $dashboardRoute }}"
               class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group
                      {{ $isDashboardActive ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 shrink-0 {{ $isDashboardActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </div>

        @if($role === 'admin')

        {{-- ===== CORE CRM ===== --}}
        <div>
            <p class="px-3 mb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Core CRM</p>
            <div class="space-y-0.5">

                @php $isActive = request()->routeIs('admin.dealers.*'); @endphp
                <a href="{{ route('admin.dealers.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Dealers
                </a>

                @php $isActive = request()->routeIs('admin.customers.*'); @endphp
                <a href="{{ route('admin.customers.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Customers
                </a>

                @php $isActive = request()->routeIs('admin.vehicles.*'); @endphp
                <a href="{{ route('admin.vehicles.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Vehicles
                </a>
            </div>
        </div>

        {{-- ===== OPERATIONS ===== --}}
        <div>
            <p class="px-3 mb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Operations</p>
            <div class="space-y-0.5">

                @php $isActive = request()->routeIs('admin.scan.*'); @endphp
                <a href="{{ route('admin.scan.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Scan QR
                </a>

                @php $isActive = request()->routeIs('admin.service_schedules.*'); @endphp
                <a href="{{ route('admin.service_schedules.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Schedules
                </a>

                @php $isActive = request()->routeIs('admin.whatsapp.*'); @endphp
                <a href="{{ route('admin.whatsapp.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    WhatsApp
                </a>
            </div>
        </div>

        {{-- ===== REPORTS ===== --}}
        <div>
            <p class="px-3 mb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Reports & Audit</p>
            <div class="space-y-0.5">

                @php $isActive = request()->routeIs('admin.reports.*'); @endphp
                <a href="{{ route('admin.reports.customers') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Reports
                </a>

                @php $isActive = request()->routeIs('admin.audit.*'); @endphp
                <a href="{{ route('admin.audit.scan_logs') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Audit Logs
                </a>
            </div>
        </div>

        {{-- ===== SYSTEM ===== --}}
        <div>
            <p class="px-3 mb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">System</p>
            <div class="space-y-0.5">

                @php $isActive = request()->routeIs('admin.users.*'); @endphp
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Users
                </a>

                @php $isActive = request()->routeIs('admin.settings.*'); @endphp
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </a>
            </div>
        </div>

        @endif {{-- end admin --}}

        @if($role === 'leader')

        {{-- ===== LEADER REPORTS ===== --}}
        <div>
            <p class="px-3 mb-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Reports</p>
            <div class="space-y-0.5">

                @php $links = [
                    ['route' => 'leader.reports.customers',             'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Customers'],
                    ['route' => 'leader.reports.vehicles',              'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'label' => 'Vehicles'],
                    ['route' => 'leader.reports.service-histories',     'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Services'],
                    ['route' => 'leader.reports.service-schedules',     'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Schedules'],
                    ['route' => 'leader.reports.whatsapp-notifications','icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label' => 'WhatsApp'],
                    ['route' => 'leader.reports.scan-logs',             'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'label' => 'Scan Logs'],
                ]; @endphp

                @foreach($links as $link)
                @php $isActive = request()->routeIs($link['route']); @endphp
                <a href="{{ route($link['route']) }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                    </svg>
                    {{ $link['label'] }}
                </a>
                @endforeach
            </div>
        </div>

        @endif {{-- end leader --}}

    </nav>

    {{-- ===== BOTTOM USER INFO ===== --}}
    <div class="shrink-0 border-t border-slate-700 p-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs uppercase shrink-0">
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

</aside>
