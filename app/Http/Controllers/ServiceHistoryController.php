<?php

namespace App\Http\Controllers;

use App\Models\ServiceHistory;
use Illuminate\Http\Request;

class ServiceHistoryController extends Controller
{
    public function index(\App\Models\Vehicle $vehicle)
    {
        $histories = ServiceHistory::with('creator')
            ->where('vehicle_id', $vehicle->vehicle_id)
            ->orderBy('service_date', 'desc')
            ->get();
        return view('admin.service_histories.index', compact('vehicle', 'histories'));
    }

    public function create(\App\Models\Vehicle $vehicle)
    {
        return view('admin.service_histories.create', compact('vehicle'));
    }

    public function store(\App\Http\Requests\StoreServiceHistoryRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        // Auto-fill customer_id from vehicle
        $vehicle = \App\Models\Vehicle::findOrFail($data['vehicle_id']);
        $data['customer_id'] = $vehicle->customer_id;

        $history = ServiceHistory::create($data);

        if (!empty($data['next_service_date'])) {
            $reminderDays = \App\Models\Setting::where('key', 'reminder_days')->first();
            $daysOffset = $reminderDays ? (int) $reminderDays->value : 3;
            
            \App\Models\ServiceSchedule::create([
                'vehicle_id' => $history->vehicle_id,
                'history_id' => $history->history_id,
                'customer_id' => $vehicle->customer_id,
                'scheduled_date' => $data['next_service_date'],
                'reminder_date' => \Carbon\Carbon::parse($data['next_service_date'])->subDays($daysOffset)->toDateString(),
                'status' => 'pending',
                'notification_status' => 'pending',
            ]);
        }

        return redirect()->route('admin.vehicles.service_histories.index', $history->vehicle_id)->with('success', 'Service history added successfully.');
    }

    /**
     * S4: Edit service history
     */
    public function edit(ServiceHistory $history)
    {
        $history->load('vehicle');
        $vehicle = $history->vehicle;
        return view('admin.service_histories.edit', compact('history', 'vehicle'));
    }

    public function update(\App\Http\Requests\StoreServiceHistoryRequest $request, ServiceHistory $history)
    {
        $data = $request->validated();
        $history->update($data);

        return redirect()->route('admin.vehicles.service_histories.index', $history->vehicle_id)->with('success', 'Service history updated.');
    }
}
