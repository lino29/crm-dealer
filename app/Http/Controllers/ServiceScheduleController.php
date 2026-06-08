<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\ServiceSchedule::with(['vehicle.customer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('scheduled_date', [$request->start_date, $request->end_date]);
        }

        $schedules = $query->orderBy('scheduled_date', 'asc')->paginate(15);
            
        return view('admin.service_schedules.index', compact('schedules'));
    }

    public function complete(\App\Models\ServiceSchedule $schedule)
    {
        $schedule->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Schedule marked as completed.');
    }

    public function cancel(\App\Models\ServiceSchedule $schedule)
    {
        $schedule->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Schedule cancelled.');
    }
}
