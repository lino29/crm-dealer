<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppNotification;
use App\Models\ServiceSchedule;
use App\Jobs\SendWhatsAppReminderJob;
use Illuminate\Http\Request;

class WhatsAppReminderController extends Controller
{
    public function index(Request $request)
    {
        $query = WhatsAppNotification::with(['customer', 'schedule.vehicle', 'creator']);

        if ($request->filled('status')) {
            $query->where('send_status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $notifications = $query->latest()->paginate(20);

        // Get schedules that need reminder
        $dueSchedules = ServiceSchedule::with(['vehicle.customer'])
            ->where('status', 'pending')
            ->where('scheduled_date', '<=', now()->addDays(3))
            ->get();

        return view('admin.whatsapp.index', compact('notifications', 'dueSchedules'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:service_schedules,schedule_id',
        ]);

        $schedule = ServiceSchedule::with(['vehicle.customer'])->findOrFail($request->schedule_id);
        $customer = $schedule->vehicle->customer;

        if (!$customer->phone) {
            return back()->with('error', 'Customer does not have a WhatsApp number.');
        }

        // Create notification record
        $template = \App\Models\Setting::getWaTemplate();
        $vehicleInfo = $schedule->vehicle->police_number . ' (' . $schedule->vehicle->brand . ')';
        $message = str_replace(
            ['{customer_name}', '{vehicle_info}', '{date}'],
            [$customer->customer_name, $vehicleInfo, $schedule->scheduled_date->format('d M Y')],
            $template
        );

        $notification = WhatsAppNotification::create([
            'schedule_id' => $schedule->schedule_id,
            'customer_id' => $customer->customer_id,
            'phone' => $customer->phone,
            'message' => $message,
            'send_status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        // Dispatch job
        SendWhatsAppReminderJob::dispatch($notification);

        return back()->with('success', 'Reminder queued for sending.');
    }

    public function retry(WhatsAppNotification $notification)
    {
        if ($notification->send_status !== 'failed') {
            return back()->with('error', 'Only failed notifications can be retried.');
        }

        $notification->update(['send_status' => 'pending']);

        if ($notification->schedule) {
            SendWhatsAppReminderJob::dispatch($notification);
        }

        return back()->with('success', 'Retry queued.');
    }
}
