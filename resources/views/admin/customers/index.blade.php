<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customers') }}
            </h2>
            <a href="{{ route('admin.customers.create') }}" class="px-4 py-2 bg-green-500 text-white rounded">Add Customer</a>
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

                {{-- Bulk Print Member Cards Form --}}
                <form id="form-bulk-print" method="POST" action="{{ route('admin.member_cards.bulk_print') }}" target="_blank">
                    @csrf
                    <div id="hidden-ids-print"></div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Kartu Member
                    </button>
                </form>

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

            function getCheckedIds() {
                return Array.from(rowCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
            }

            function buildHiddenInputs(container, ids) {
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
                    buildHiddenInputs(hiddenExcel,  ids);
                    buildHiddenInputs(hiddenPdf,    ids);
                    buildHiddenInputs(hiddenPrint,  ids);
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
</x-app-layout>
