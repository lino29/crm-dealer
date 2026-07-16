<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customers') }}
            </h2>
            <div class="flex items-center gap-2">
                @if(in_array(Auth::user()->role?->role_name, ['admin', 'admin_support']))
                <button type="button"
                        x-data
                        @click="$dispatch('open-import-modal')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Excel
                </button>
                @endif
                <a href="{{ route('admin.customers.create') }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded shadow transition">Add Customer</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Session Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Import Result: Partial Success with Errors --}}
            @if(session('import_success'))
                <div class="bg-amber-50 border border-amber-300 text-amber-800 p-4 mb-4 rounded-lg" role="alert">
                    <p class="font-semibold mb-2">⚠️ {{ session('import_success') }} Namun terdapat baris yang gagal:</p>
                    <ul class="text-sm list-disc list-inside max-h-40 overflow-y-auto space-y-0.5">
                        @foreach(session('import_errors', []) as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Search Bar --}}
            <div class="mb-4 bg-white p-4 shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('admin.customers.index') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Name, Phone, Member Code, Police Number..." class="flex-1 rounded border-gray-300">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded text-center">Clear</a>
                    @endif
                </form>
            </div>

            {{-- Bulk Action Toolbar (hidden until a checkbox is checked) --}}
            <div id="bulk-toolbar"
                 class="hidden mb-4 bg-indigo-50 border border-indigo-200 rounded-lg p-4 flex flex-wrap items-center gap-3 shadow-sm">
                <span class="text-sm font-semibold text-indigo-700 mr-2">
                    <span id="selected-count">0</span> pelanggan dipilih:
                </span>

                {{-- Bulk Export Excel Form --}}
                <form id="form-bulk-excel" method="POST" action="{{ route('admin.customers.bulk_export_excel') }}">
                    @csrf
                    <div id="hidden-ids-excel"></div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md shadow transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Excel
                    </button>
                </form>

                {{-- Bulk Export PDF Form --}}
                <form id="form-bulk-pdf" method="POST" action="{{ route('admin.customers.bulk_export_pdf') }}">
                    @csrf
                    <div id="hidden-ids-pdf"></div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md shadow transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Export PDF
                    </button>
                </form>

                @if(in_array(Auth::user()->role?->role_name, ['admin', 'admin_support']))
                {{-- Bulk Print Member Cards — CR-80 Card Size (1 per page) --}}
                <form id="form-bulk-print" method="POST" action="{{ route('admin.member_cards.bulk_print') }}" target="_blank">
                    @csrf
                    <div id="hidden-ids-print"></div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow transition"
                            title="Cetak PDF — setiap halaman berukuran persis 1 kartu CR-80 (85.6 × 54 mm)">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Kartu (CR-80)
                    </button>
                </form>

                {{-- Bulk Print Member Cards — A4 Sheet (many cards per page) --}}
                <form id="form-bulk-print-a4" method="POST" action="{{ route('admin.member_cards.bulk_print_a4') }}" target="_blank">
                    @csrf
                    <div id="hidden-ids-print-a4"></div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md shadow transition"
                            title="Cetak PDF — kertas A4, susunan 2 kolom × 5 baris (hingga 10 kartu per halaman)">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                        </svg>
                        Print Kartu (A4 Sheet)
                    </button>
                </form>
                @endif

                {{-- Deselect All --}}
                <button type="button" id="btn-deselect-all"
                        class="ml-auto text-sm text-gray-500 hover:text-gray-700 underline">
                    Batal Pilih Semua
                </button>
            </div>

            {{-- Customer Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 px-3 w-10">
                                    <input type="checkbox" id="select-all"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           title="Pilih semua">
                                </th>
                                <th class="border-b py-2 px-4">Name</th>
                                <th class="border-b py-2 px-4">Phone</th>
                                <th class="border-b py-2 px-4">Dealer</th>
                                <th class="border-b py-2 px-4">Status</th>
                                <th class="border-b py-2 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border-b py-2 px-3">
                                    <input type="checkbox" name="customer_ids[]"
                                           value="{{ $customer->customer_id }}"
                                           class="row-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                </td>
                                <td class="border-b py-2 px-4">{{ $customer->customer_name }}</td>
                                <td class="border-b py-2 px-4">{{ $customer->phone }}</td>
                                <td class="border-b py-2 px-4">{{ $customer->dealer->dealer_name ?? '-' }}</td>
                                <td class="border-b py-2 px-4">
                                    <span class="px-2 py-1 rounded {{ $customer->status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td class="border-b py-2 px-4 flex space-x-2">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-indigo-500 hover:underline">Detail</a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="text-blue-500 hover:underline">Edit</a>
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Set to inactive?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Inactive</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const selectAll       = document.getElementById('select-all');
            const rowCheckboxes   = document.querySelectorAll('.row-checkbox');
            const bulkToolbar     = document.getElementById('bulk-toolbar');
            const selectedCount   = document.getElementById('selected-count');
            const btnDeselect     = document.getElementById('btn-deselect-all');

            const hiddenExcel     = document.getElementById('hidden-ids-excel');
            const hiddenPdf       = document.getElementById('hidden-ids-pdf');
            const hiddenPrint     = document.getElementById('hidden-ids-print');
            const hiddenPrintA4   = document.getElementById('hidden-ids-print-a4');

            function getCheckedIds() {
                return Array.from(rowCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
            }

            function buildHiddenInputs(container, ids) {
                if (!container) return;
                container.innerHTML = '';
                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type  = 'hidden';
                    input.name  = 'customer_ids[]';
                    input.value = id;
                    container.appendChild(input);
                });
            }

            function updateToolbar() {
                const ids = getCheckedIds();
                const count = ids.length;

                selectedCount.textContent = count;

                if (count > 0) {
                    bulkToolbar.classList.remove('hidden');
                    buildHiddenInputs(hiddenExcel,    ids);
                    buildHiddenInputs(hiddenPdf,      ids);
                    buildHiddenInputs(hiddenPrint,    ids);
                    buildHiddenInputs(hiddenPrintA4,  ids);
                } else {
                    bulkToolbar.classList.add('hidden');
                }

                // Update "select all" intermediate state
                selectAll.indeterminate = count > 0 && count < rowCheckboxes.length;
                selectAll.checked = count === rowCheckboxes.length && rowCheckboxes.length > 0;
            }

            selectAll.addEventListener('change', function () {
                rowCheckboxes.forEach(cb => cb.checked = this.checked);
                updateToolbar();
            });

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateToolbar);
            });

            btnDeselect.addEventListener('click', function () {
                rowCheckboxes.forEach(cb => cb.checked = false);
                selectAll.checked = false;
                updateToolbar();
            });
        })();
    </script>

    {{-- ===== Import Data Excel Modal ===== --}}
    @if(in_array(Auth::user()->role?->role_name, ['admin', 'admin_support']))
    @php $dealers = App\Models\Dealer::where('status', 'active')->get(); @endphp
    <div x-data="{ open: false }"
         @open-import-modal.window="open = true"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

        {{-- Modal Box --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 z-10"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Import Data Excel</h3>
                        <p class="text-xs text-slate-500">Pelanggan & Kendaraan (format: .xlsx, .xls, .csv)</p>
                    </div>
                </div>
                <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form action="{{ route('admin.customers.import') }}" method="POST" enctype="multipart/form-data"
                  x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <div class="px-6 py-5 space-y-4">

                    {{-- Dealer Selector --}}
                    <div>
                        <label for="import_dealer_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Dealer Tujuan <span class="text-red-500">*</span></label>
                        <select name="dealer_id" id="import_dealer_id" required
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- Pilih Dealer --</option>
                            @foreach($dealers as $dealer)
                                <option value="{{ $dealer->dealer_id }}">{{ $dealer->dealer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- File Input --}}
                    <div>
                        <label for="import_file" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">File Excel / CSV <span class="text-red-500">*</span></label>
                        <input type="file" name="file" id="import_file" required
                               accept=".xlsx,.xls,.csv"
                               class="w-full text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                        <p class="text-[10px] text-slate-400 mt-1">Maks. ukuran file: 10 MB</p>
                    </div>

                    {{-- Download Template --}}
                    <div class="flex items-center gap-2 bg-slate-50 rounded-xl px-4 py-3 border border-slate-200">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-xs text-slate-600 flex-1">Belum punya template? Unduh terlebih dahulu.</span>
                        <a href="{{ route('admin.customers.import.template') }}"
                           class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition">Unduh Template</a>
                    </div>

                    {{-- Info note --}}
                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        Pelanggan yang sudah terdaftar (berdasarkan no. HP) tidak akan digandakan. Kendaraan dengan plat nomor yang sama juga akan dilewati.
                        Kartu member QR akan otomatis diterbitkan untuk pelanggan baru.
                    </p>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" :disabled="loading"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-xl shadow transition">
                        <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span x-text="loading ? 'Memproses...' : 'Mulai Import'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</x-app-layout>
