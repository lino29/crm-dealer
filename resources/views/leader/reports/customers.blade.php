<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Customer Report') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div><label class="block text-sm">Dealer</label><select name="dealer_id" class="rounded border-gray-300"><option value="">All</option>@foreach($dealers as $d)<option value="{{ $d->dealer_id }}" {{ request('dealer_id')==$d->dealer_id ? 'selected' : '' }}>{{ $d->dealer_name }}</option>@endforeach</select></div>
                    <div><label class="block text-sm">Status</label><select name="status" class="rounded border-gray-300"><option value="">All</option><option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option><option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option></select></div>
                    <div><label class="block text-sm">From</label><input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded border-gray-300"></div>
                    <div><label class="block text-sm">To</label><input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded border-gray-300"></div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
                    <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['export'=>'pdf'])) }}" class="px-4 py-2 bg-red-500 text-white rounded">PDF</a>
                    <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['export'=>'excel'])) }}" class="px-4 py-2 bg-green-500 text-white rounded">Excel</a>
                </form>
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50"><tr><th class="px-4 py-2">Name</th><th class="px-4 py-2">Phone</th><th class="px-4 py-2">Dealer</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Registered</th></tr></thead>
                    <tbody>
                    @forelse($customers as $c)
                    <tr class="border-b"><td class="px-4 py-2">{{ $c->customer_name }}</td><td class="px-4 py-2">{{ $c->phone }}</td><td class="px-4 py-2">{{ $c->dealer->dealer_name ?? '-' }}</td><td class="px-4 py-2"><span class="px-2 py-1 rounded text-xs {{ $c->status==='active'?'bg-green-200 text-green-800':'bg-red-200 text-red-800' }}">{{ ucfirst($c->status) }}</span></td><td class="px-4 py-2">{{ $c->created_at->format('d M Y') }}</td></tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500 italic">No data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
