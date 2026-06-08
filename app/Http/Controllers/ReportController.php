<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function customers(Request $request)
    {
        $query = \App\Models\Customer::with('dealer')->latest();
        
        if ($request->filled('dealer_id')) {
            $query->where('dealer_id', $request->dealer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $customers = $query->get();
        $dealers = \App\Models\Dealer::all();

        if ($request->has('export')) {
            if ($request->export === 'excel') {
                return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CustomersExport($customers), 'customers_report.xlsx');
            } elseif ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.exports.customers', compact('customers'));
                return $pdf->download('customers_report.pdf');
            }
        }

        // Detect if leader or admin for view
        $viewPrefix = auth()->user()->role->role_name === 'leader' ? 'leader' : 'admin';
        return view("{$viewPrefix}.reports.customers", compact('customers', 'dealers'));
    }

    public function vehicles(Request $request)
    {
        $query = \App\Models\Vehicle::with('customer.dealer')->latest();

        if ($request->filled('dealer_id')) {
            $query->whereHas('customer', fn($q) => $q->where('dealer_id', $request->dealer_id));
        }
        if ($request->filled('brand')) {
            $query->where('brand', 'like', "%{$request->brand}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vehicles = $query->get();
        $dealers = \App\Models\Dealer::all();

        if ($request->has('export')) {
            if ($request->export === 'excel') {
                return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\VehiclesExport($vehicles), 'vehicles_report.xlsx');
            } elseif ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.exports.vehicles', compact('vehicles'));
                return $pdf->download('vehicles_report.pdf');
            }
        }

        return view('leader.reports.vehicles', compact('vehicles', 'dealers'));
    }

    public function services(Request $request)
    {
        $query = \App\Models\ServiceHistory::with(['vehicle.customer'])->latest();

        if ($request->filled('dealer_id')) {
            $query->whereHas('vehicle.customer', fn($q) => $q->where('dealer_id', $request->dealer_id));
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('service_date', [$request->start_date, $request->end_date]);
        }

        $services = $query->get();
        $dealers = \App\Models\Dealer::all();

        if ($request->has('export')) {
            if ($request->export === 'excel') {
                return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ServicesExport($services), 'services_report.xlsx');
            } elseif ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.exports.services', compact('services'));
                return $pdf->download('services_report.pdf');
            }
        }

        $viewPrefix = auth()->user()->role->role_name === 'leader' ? 'leader' : 'admin';
        return view("{$viewPrefix}.reports.services", compact('services', 'dealers'));
    }

    public function serviceHistories(Request $request)
    {
        return $this->services($request);
    }

    public function serviceSchedules(Request $request)
    {
        $query = \App\Models\ServiceSchedule::with(['vehicle.customer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('scheduled_date', [$request->start_date, $request->end_date]);
        }

        $schedules = $query->get();

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('leader.reports.exports.schedules', compact('schedules'));
                return $pdf->download('schedules_report.pdf');
            }
        }

        return view('leader.reports.schedules', compact('schedules'));
    }

    public function whatsappNotifications(Request $request)
    {
        $query = \App\Models\WhatsAppNotification::with(['customer', 'creator'])->latest();

        if ($request->filled('send_status')) {
            $query->where('send_status', $request->send_status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $notifications = $query->get();

        return view('leader.reports.whatsapp', compact('notifications'));
    }

    public function scanLogs(Request $request)
    {
        $query = \App\Models\ScanLog::with(['memberCard.customer', 'scanner'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('scanned_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $logs = $query->get();

        return view('leader.reports.scan_logs', compact('logs'));
    }

    public function exportPdf(Request $request, $type)
    {
        return $this->handleExport($request, $type, 'pdf');
    }

    public function exportExcel(Request $request, $type)
    {
        return $this->handleExport($request, $type, 'excel');
    }

    private function handleExport(Request $request, $type, $format)
    {
        $request->merge(['export' => $format]);

        return match($type) {
            'customers' => $this->customers($request),
            'vehicles' => $this->vehicles($request),
            'service-histories' => $this->services($request),
            default => abort(404),
        };
    }
}
