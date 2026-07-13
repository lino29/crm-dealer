<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Customer::with('dealer')->latest();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('memberCards', function($q2) use ($search) {
                      $q2->where('member_code', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehicles', function($q2) use ($search) {
                      $q2->where('police_number', 'like', "%{$search}%");
                  });
            });
        }

        $customers = $query->paginate(10)->appends($request->query());
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $dealers = \App\Models\Dealer::where('status', 'active')->get();
        return view('admin.customers.create', compact('dealers'));
    }

    public function store(\App\Http\Requests\StoreCustomerRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        \App\Models\Customer::create($data);
        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(\App\Models\Customer $customer)
    {
        $customer->load([
            'dealer',
            'creator',
            'memberCard',
            'vehicles.serviceHistories',
        ]);

        // Collect all service histories across all vehicles, sorted newest first
        $serviceHistories = $customer->vehicles
            ->flatMap(fn($v) => $v->serviceHistories->map(fn($h) => $h->setRelation('vehicle', $v)))
            ->sortByDesc('service_date');

        // Scan logs and WA notifications
        $scanLogs = $customer->scanLogs()->with('scanner')->latest('scanned_at')->limit(30)->get();
        $waNotifications = $customer->whatsappNotifications()->latest()->limit(30)->get();

        return view('admin.customers.show', compact('customer', 'serviceHistories', 'scanLogs', 'waNotifications'));
    }

    public function edit(\App\Models\Customer $customer)
    {
        $dealers = \App\Models\Dealer::where('status', 'active')->get();
        return view('admin.customers.edit', compact('customer', 'dealers'));
    }

    public function update(\App\Http\Requests\UpdateCustomerRequest $request, \App\Models\Customer $customer)
    {
        $customer->update($request->validated());
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(\App\Models\Customer $customer)
    {
        $customer->update(['status' => 'inactive']);
        return redirect()->route('admin.customers.index')->with('success', 'Customer set to inactive.');
    }

    /**
     * Bulk export selected customers as Excel.
     */
    public function bulkExportExcel(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('customer_ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.customers.index')->with('error', 'Pilih minimal satu pelanggan untuk diekspor.');
        }

        $customers = \App\Models\Customer::with('dealer')
            ->whereIn('customer_id', $ids)
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\CustomersExport($customers),
            'customers_export_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    /**
     * Bulk export selected customers as PDF.
     */
    public function bulkExportPdf(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('customer_ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.customers.index')->with('error', 'Pilih minimal satu pelanggan untuk diekspor.');
        }

        $customers = \App\Models\Customer::with('dealer')
            ->whereIn('customer_id', $ids)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.exports.customers', compact('customers'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('customers_export_' . now()->format('Ymd_His') . '.pdf');
    }
}
