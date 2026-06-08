<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function scanLogs(Request $request)
    {
        $query = \App\Models\ScanLog::with(['memberCard.customer', 'scanner']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('scanned_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $logs = $query->latest()->paginate(20);
        return view('admin.audit.scan_logs', compact('logs'));
    }

    public function notificationLogs(Request $request)
    {
        $query = \App\Models\WhatsAppNotification::with(['customer', 'schedule.vehicle', 'creator']);

        if ($request->filled('send_status')) {
            $query->where('send_status', $request->send_status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $notifications = $query->latest()->paginate(20);
        return view('admin.audit.notification_logs', compact('notifications'));
    }
}
