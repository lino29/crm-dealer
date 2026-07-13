<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-slate-500 text-sm">
            <a href="{{ route('admin.customers.index') }}" class="hover:text-indigo-600 transition">Customers</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-800 font-semibold">{{ $customer->customer_name }}</span>
        </div>
    </x-slot>

    <div class="p-6 space-y-6"
         x-data="{ activeTab: 'profile' }">

        {{-- ===== HERO CUSTOMER CARD ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="h-20 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600"></div>
            <div class="px-6 pb-6 -mt-10 flex flex-wrap items-end gap-4">
                {{-- Avatar --}}
                <div class="w-20 h-20 rounded-2xl bg-white border-4 border-white shadow-lg flex items-center justify-center shrink-0">
                    <span class="text-2xl font-bold text-indigo-600 uppercase">{{ substr($customer->customer_name, 0, 2) }}</span>
                </div>
                {{-- Info --}}
                <div class="flex-1 min-w-0 pb-1">
                    <h1 class="text-xl font-bold text-slate-900 truncate">{{ $customer->customer_name }}</h1>
                    <p class="text-sm text-slate-500">{{ $customer->phone }} &bull; {{ $customer->dealer->dealer_name ?? '-' }}</p>
                </div>
                {{-- Status Badge + Actions --}}
                <div class="flex items-center gap-3 pb-1 ml-auto">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $customer->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ ucfirst($customer->status) }}
                    </span>
                    <a href="{{ route('admin.customers.edit', $customer) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ===== TABS ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Tab Navigation --}}
            <div class="flex border-b border-slate-200 px-6 overflow-x-auto">
                @php
                    $tabs = [
                        ['key' => 'profile',   'label' => 'Profil & Kartu Member', 'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['key' => 'vehicles',  'label' => 'Kendaraan',             'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                        ['key' => 'services',  'label' => 'Riwayat Servis',        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['key' => 'activity',  'label' => 'Log Aktivitas',         'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ];
                @endphp
                @foreach($tabs as $tab)
                <button
                    @click="activeTab = '{{ $tab['key'] }}'"
                    :class="activeTab === '{{ $tab['key'] }}' ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' : 'text-slate-500 hover:text-slate-700 border-b-2 border-transparent'"
                    class="flex items-center gap-2 px-4 py-4 text-sm whitespace-nowrap transition-colors duration-150 focus:outline-none shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/></svg>
                    {{ $tab['label'] }}
                </button>
                @endforeach
            </div>

            {{-- ======================= --}}
            {{-- TAB 1: PROFIL & KARTU  --}}
            {{-- ======================= --}}
            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Profile Details --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Data Diri</h3>
                        <dl class="space-y-3">
                            @foreach([
                                ['label' => 'Nama Lengkap', 'value' => $customer->customer_name],
                                ['label' => 'Nomor Telepon', 'value' => $customer->phone],
                                ['label' => 'Jenis Kelamin', 'value' => ucfirst($customer->gender)],
                                ['label' => 'Tanggal Lahir', 'value' => $customer->birth_date?->format('d M Y') ?? '-'],
                                ['label' => 'Alamat', 'value' => $customer->address],
                                ['label' => 'Dealer', 'value' => $customer->dealer->dealer_name ?? '-'],
                                ['label' => 'Bulan USK', 'value' => $customer->usk_month],
                                ['label' => 'Didaftarkan oleh', 'value' => $customer->creator->name ?? '-'],
                            ] as $item)
                            <div class="flex gap-3">
                                <dt class="text-sm text-slate-500 w-36 shrink-0">{{ $item['label'] }}</dt>
                                <dd class="text-sm font-medium text-slate-800">{{ $item['value'] }}</dd>
                            </div>
                            @endforeach
                        </dl>
                    </div>

                    {{-- Member Card Manager --}}
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Kartu Member</h3>

                        @if($customer->memberCard)
                        <div class="rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-purple-50 p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-lg font-bold text-indigo-700 tracking-widest">{{ $customer->memberCard->member_code }}</span>
                                <span class="text-xs px-2 py-1 rounded-full {{ $customer->memberCard->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} font-semibold">
                                    {{ ucfirst($customer->memberCard->status) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-xs text-slate-600">
                                <div><span class="block text-slate-400">Diterbitkan</span> {{ $customer->memberCard->issued_date?->format('d M Y') ?? '-' }}</div>
                                <div><span class="block text-slate-400">Jumlah Cetak</span> {{ $customer->memberCard->print_count }}×</div>
                                <div class="col-span-2"><span class="block text-slate-400">Terakhir Dicetak</span> {{ $customer->memberCard->last_printed_at?->format('d M Y H:i') ?? 'Belum pernah' }}</div>
                            </div>
                            @if(in_array(Auth::user()->role?->role_name, ['admin', 'admin_support']))
                            <div class="flex flex-wrap gap-2 pt-1">
                                <a href="{{ route('admin.member_cards.preview', $customer) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Preview
                                </a>
                                <a href="{{ route('admin.member_cards.print', $customer) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Print (CR-80)
                                </a>
                                <form action="{{ route('admin.member_cards.regenerate', $customer) }}" method="POST"
                                      onsubmit="return confirm('Regenerate token? Kartu lama akan dinonaktifkan.')">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Regenerate
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="rounded-xl border-2 border-dashed border-slate-200 p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <p class="text-sm text-slate-500 {{ in_array(Auth::user()->role?->role_name, ['admin', 'admin_support']) ? 'mb-4' : '' }}">
                                Belum ada kartu member yang diterbitkan.
                            </p>
                            @if(in_array(Auth::user()->role?->role_name, ['admin', 'admin_support']))
                            <form action="{{ route('admin.member_cards.generate', $customer) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Generate Kartu Member
                                </button>
                            </form>
                            @else
                                <span class="text-xs text-slate-400 block mt-2">Hubungi Admin Support untuk pembuatan kartu member pelanggan ini.</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ======================= --}}
            {{-- TAB 2: KENDARAAN       --}}
            {{-- ======================= --}}
            <div x-show="activeTab === 'vehicles'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Daftar Kendaraan ({{ $customer->vehicles->count() }})</h3>
                        <a href="{{ route('admin.vehicles.create', ['customer_id' => $customer->customer_id]) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Kendaraan
                        </a>
                    </div>
                    @if($customer->vehicles->count() > 0)
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3">Nomor Polisi</th>
                                    <th class="px-5 py-3">Merek / Model</th>
                                    <th class="px-5 py-3">Warna</th>
                                    <th class="px-5 py-3">Tgl Beli</th>
                                    <th class="px-5 py-3">Status Mobil</th>
                                    <th class="px-5 py-3">Status STNK</th>
                                    <th class="px-5 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($customer->vehicles as $vehicle)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-5 py-3.5 font-semibold text-slate-800 font-mono">{{ $vehicle->police_number }}</td>
                                    <td class="px-5 py-3.5 text-slate-700">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $vehicle->color }}</td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $vehicle->purchase_date?->format('d M Y') ?? '-' }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $vehicle->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($vehicle->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex flex-col gap-1.5">
                                            @php
                                                $stnkBadgeColor = match($vehicle->stnk_status) {
                                                    'ready' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                                    'diserahkan' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    default => 'bg-amber-100 text-amber-700 border-amber-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center w-max px-2.5 py-1 rounded-full text-xs font-bold border {{ $stnkBadgeColor }}">
                                                {{ ucfirst($vehicle->stnk_status) }}
                                            </span>

                                            {{-- Details --}}
                                            @if($vehicle->stnk_status === 'ready' && $vehicle->stnk_received_at)
                                                <span class="text-[10px] text-slate-400">Diterima: {{ $vehicle->stnk_received_at->format('d M H:i') }}</span>
                                            @elseif($vehicle->stnk_status === 'diserahkan' && $vehicle->stnk_handed_over_at)
                                                <span class="text-[10px] text-slate-400">Diserahkan: {{ $vehicle->stnk_handed_over_at->format('d M H:i') }}</span>
                                            @endif

                                            {{-- Transition Controls (admin & admin_stnk only) --}}
                                            @if(in_array(Auth::user()->role?->role_name, ['admin', 'admin_stnk']))
                                                @if($vehicle->stnk_status === 'proses')
                                                    <form action="{{ route('admin.vehicles.update_stnk', $vehicle) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="stnk_status" value="ready">
                                                        <button type="submit" class="inline-flex items-center text-[10px] font-bold text-indigo-600 hover:text-indigo-800 transition">
                                                            Set Ready &raquo;
                                                        </button>
                                                    </form>
                                                @elseif($vehicle->stnk_status === 'ready')
                                                    <form action="{{ route('admin.vehicles.update_stnk', $vehicle) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="stnk_status" value="diserahkan">
                                                        <button type="submit" class="inline-flex items-center text-[10px] font-bold text-emerald-600 hover:text-emerald-800 transition">
                                                            Serahkan ke Konsumen &raquo;
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.vehicles.service_histories.index', $vehicle) }}" class="text-indigo-600 hover:underline text-xs font-medium">History</a>
                                            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-blue-600 hover:underline text-xs font-medium">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        <p class="text-sm">Belum ada kendaraan terdaftar.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ======================= --}}
            {{-- TAB 3: RIWAYAT SERVIS  --}}
            {{-- ======================= --}}
            <div x-show="activeTab === 'services'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none">
                <div class="p-6">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Riwayat Servis ({{ $serviceHistories->count() }})</h3>
                    @if($serviceHistories->count() > 0)
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3">Tanggal</th>
                                    <th class="px-5 py-3">Kendaraan</th>
                                    <th class="px-5 py-3">Deskripsi</th>
                                    <th class="px-5 py-3">Biaya</th>
                                    <th class="px-5 py-3">Teknisi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($serviceHistories as $history)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap">{{ $history->service_date?->format('d M Y') ?? '-' }}</td>
                                    <td class="px-5 py-3.5 font-mono text-xs text-slate-600">{{ $history->vehicle->police_number ?? '-' }}</td>
                                    <td class="px-5 py-3.5 text-slate-700 max-w-xs truncate">{{ $history->description ?? '-' }}</td>
                                    <td class="px-5 py-3.5 text-slate-700">{{ $history->cost ? 'Rp ' . number_format($history->cost, 0, ',', '.') : '-' }}</td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $history->technician ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-sm">Belum ada riwayat servis.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ========================= --}}
            {{-- TAB 4: LOG AKTIVITAS      --}}
            {{-- ========================= --}}
            <div x-show="activeTab === 'activity'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none">
                <div class="p-6 space-y-6">

                    {{-- Scan Logs --}}
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">
                            Scan QR Logs ({{ $scanLogs->count() }} terbaru)
                        </h3>
                        @if($scanLogs->count() > 0)
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-5 py-3">Waktu</th>
                                        <th class="px-5 py-3">Status</th>
                                        <th class="px-5 py-3">Dipindai oleh</th>
                                        <th class="px-5 py-3">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($scanLogs as $log)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-5 py-3 text-slate-600 whitespace-nowrap">{{ $log->scanned_at?->format('d M Y H:i') ?? '-' }}</td>
                                        <td class="px-5 py-3">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $log->status === 'success' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-slate-700">{{ $log->scanner->name ?? '-' }}</td>
                                        <td class="px-5 py-3 text-slate-500 text-xs">{{ $log->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-slate-400 italic">Belum ada log pemindaian QR.</p>
                        @endif
                    </div>

                    <hr class="border-slate-100">

                    {{-- WhatsApp Notifications --}}
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">
                            Notifikasi WhatsApp ({{ $waNotifications->count() }} terbaru)
                        </h3>
                        @if($waNotifications->count() > 0)
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-5 py-3">Waktu</th>
                                        <th class="px-5 py-3">Status</th>
                                        <th class="px-5 py-3">Pesan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($waNotifications as $notif)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-5 py-3 text-slate-600 whitespace-nowrap">{{ $notif->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-5 py-3">
                                            @php $color = match($notif->send_status) {
                                                'sent'   => 'bg-emerald-100 text-emerald-700',
                                                'failed' => 'bg-red-100 text-red-700',
                                                default  => 'bg-amber-100 text-amber-700',
                                            }; @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                                {{ ucfirst($notif->send_status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-slate-600 max-w-xs truncate">{{ $notif->message }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-slate-400 italic">Belum ada notifikasi WhatsApp yang dikirim.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>{{-- end tab card --}}

    </div>
</x-app-layout>
