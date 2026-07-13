<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Vehicle::with('customer')->latest();

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('police_number', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        // STNK status filter
        if ($stnkStatus = $request->input('stnk_status')) {
            $query->where('stnk_status', $stnkStatus);
        }

        $vehicles = $query->paginate(15)->withQueryString();

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create(Request $request)
    {
        $customer_id = $request->query('customer_id');
        $customer = \App\Models\Customer::find($customer_id);
        $customers = \App\Models\Customer::where('status', 'active')->get();
        return view('admin.vehicles.create', compact('customer', 'customers'));
    }

    public function store(\App\Http\Requests\StoreVehicleRequest $request)
    {
        $vehicle = \App\Models\Vehicle::create($request->validated());
        return redirect()->route('admin.customers.show', $vehicle->customer_id)->with('success', 'Vehicle added successfully.');
    }

    public function show(\App\Models\Vehicle $vehicle)
    {
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(\App\Models\Vehicle $vehicle)
    {
        $customers = \App\Models\Customer::where('status', 'active')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'customers'));
    }

    public function update(\App\Http\Requests\UpdateVehicleRequest $request, \App\Models\Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
        return redirect()->route('admin.customers.show', $vehicle->customer_id)->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(\App\Models\Vehicle $vehicle)
    {
        $vehicle->update(['status' => 'inactive']);
        return redirect()->route('admin.customers.show', $vehicle->customer_id)->with('success', 'Vehicle set to inactive.');
    }

    /**
     * Update the STNK status of a vehicle (proses -> ready -> diserahkan).
     */
    public function updateStnkStatus(Request $request, \App\Models\Vehicle $vehicle)
    {
        $request->validate([
            'stnk_status' => 'required|in:proses,ready,diserahkan',
        ]);

        $status = $request->input('stnk_status');
        $updateData = ['stnk_status' => $status];

        if ($status === 'ready') {
            $updateData['stnk_received_at'] = now();
        } elseif ($status === 'diserahkan') {
            $updateData['stnk_handed_over_at'] = now();
        }

        $vehicle->update($updateData);

        return redirect()->route('admin.customers.show', $vehicle->customer_id)
            ->with('success', 'Status STNK kendaraan ' . $vehicle->police_number . ' berhasil diupdate menjadi ' . ucfirst($status) . '.');
    }
}
