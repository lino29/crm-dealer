<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">{{ __('Vehicles & STNK Tracking') }}</h2>
    </x-slot>

    <div class="p-6 space-y-6">

        {{-- ===== FILTERS CARD ===== --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <form method="GET" action="{{ route('admin.vehicles.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="search" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari Kendaraan</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Cari nopol, merek, model, atau nama pemilik...">
                        <div class="absolute left-3.5 top-2.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- STNK Status --}}
                <div>
                    <label for="stnk_status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status STNK</label>
                    <select name="stnk_status" id="stnk_status"
                            class="w-full py-2 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Semua Status</option>
                        <option value="proses" {{ request('stnk_status') === 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="ready" {{ request('stnk_status') === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="diserahkan" {{ request('stnk_status') === 'diserahkan' ? 'selected' : '' }}>Diserahkan</option>
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 justify-center inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        Filter
                    </button>
                    @if(request('search') || request('stnk_status'))
                        <a href="{{ route('admin.vehicles.index') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ===== DATA TABLE ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5">Nomor Polisi</th>
                            <th class="px-5 py-3.5">Merek / Model</th>
                            <th class="px-5 py-3.5">Tahun / Warna</th>
                            <th class="px-5 py-3.5">Pemilik (Customer)</th>
                            <th class="px-5 py-3.5">Status Mobil</th>
                            <th class="px-5 py-3.5">Status STNK</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-slate-50 transition">
                            {{-- Police Number --}}
                            <td class="px-5 py-4 font-semibold text-slate-800 font-mono text-sm">{{ $vehicle->police_number }}</td>
                            {{-- Brand & Model --}}
                            <td class="px-5 py-4 text-slate-700 font-medium">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                            {{-- Year & Color --}}
                            <td class="px-5 py-4 text-slate-500">
                                <span>{{ $vehicle->production_year ?? '-' }}</span> &bull; 
                                <span class="capitalize text-xs">{{ $vehicle->color ?? '-' }}</span>
                            </td>
                            {{-- Owner Name --}}
                            <td class="px-5 py-4 text-slate-800">
                                @if($vehicle->customer)
                                    <a href="{{ route('admin.customers.show', $vehicle->customer) }}" class="text-indigo-600 hover:underline font-semibold">
                                        {{ $vehicle->customer->customer_name }}
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            {{-- Vehicle Status --}}
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $vehicle->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($vehicle->status) }}
                                </span>
                            </td>
                            {{-- STNK Status & Quick Transition --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $stnkBadgeColor = match($vehicle->stnk_status) {
                                            'ready' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                            'diserahkan' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            default => 'bg-amber-100 text-amber-700 border-amber-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $stnkBadgeColor }}">
                                        {{ ucfirst($vehicle->stnk_status) }}
                                    </span>

                                    {{-- Transition Controls (admin & admin_stnk only) --}}
                                    @if(in_array(Auth::user()->role?->role_name, ['admin', 'admin_stnk']))
                                        @if($vehicle->stnk_status === 'proses')
                                            <form action="{{ route('admin.vehicles.update_stnk', $vehicle) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="stnk_status" value="ready">
                                                <button type="submit" class="inline-flex items-center text-[10px] font-bold text-indigo-600 hover:text-indigo-800 transition hover:underline">
                                                    Set Ready &raquo;
                                                </button>
                                            </form>
                                        @elseif($vehicle->stnk_status === 'ready')
                                            <form action="{{ route('admin.vehicles.update_stnk', $vehicle) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="stnk_status" value="diserahkan">
                                                <button type="submit" class="inline-flex items-center text-[10px] font-bold text-emerald-600 hover:text-emerald-800 transition hover:underline">
                                                    Serahkan &raquo;
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            {{-- General Actions --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3.5">
                                    <a href="{{ route('admin.vehicles.service_histories.index', $vehicle) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold transition">History</a>
                                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400 italic">
                                Tidak ada data kendaraan ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($vehicles->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
